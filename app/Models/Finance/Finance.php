<?php

namespace App\Models\Finance;

use App\Models\Company\Company;
use App\Models\Transaction\Transaction;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Finance extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function paymentFirst()
    {
        return $this->belongsTo(FinancePayment::class, 'finance_id')->where('order', 'asc');
    }

    public function payments()
    {
        return $this->hasMany(FinancePayment::class, 'finance_id');
    }

    public function items()
    {
        return $this->hasMany(FinanceItem::class, 'finance_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('description', 'like', '%' . $search . '%')
                ->orWhere('date', 'like', '%' . $search . '%')
                ->orWhere('type', 'like', '%' . $search . '%')
                ->orWhere('sub_total', 'like', '%' . $search . '%')
                ->orWhere('discount', 'like', '%' . $search . '%')
                ->orWhere('tax', 'like', '%' . $search . '%')
                ->orWhere('grand_total', 'like', '%' . $search . '%');
        }
        return $query;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            $modelCreate->company_id = $modelCreate->company_id ?? auth()->user()?->company_id;
            $modelCreate->code = static::generateUniqueCode();
        });
    }

    public static function generateUniqueCode(string $prefix = 'FIN', int $maxRetries = 10): string
    {
        $date = now()->format('ymd');

        // Gunakan database transaction untuk menghindari race condition
        return DB::transaction(function () use ($prefix, $date, $maxRetries) {
            $retry = 0;

            do {
                // Hitung dengan konsisten - termasuk soft deleted
                $count = static::withTrashed()
                    ->whereDate('created_at', now()->toDateString())
                    ->count() + 1 + $retry;

                $code = $prefix . $date . str_pad($count, 4, '0', STR_PAD_LEFT);

                // Cek existence dengan lebih robust
                $exists = static::withTrashed()
                    ->where('code', $code)
                    ->exists();

                if (!$exists) {
                    return trim($code); // Pastikan tidak ada trailing whitespace
                }

                $retry++;
            } while ($retry < $maxRetries);

            // Fallback ke timestamp microsecond jika semua retry gagal
            return $prefix . $date . '-' . now()->format('His') . substr(microtime(), 2, 6);
        });
    }

    // Alternative method yang lebih robust dengan locking
    public static function generateUniqueCodeWithLock(string $prefix = 'FIN'): string
    {
        $date = now()->format('ymd');

        return DB::transaction(function () use ($prefix, $date) {
            // Lock table untuk menghindari race condition
            DB::statement('LOCK TABLE finances IN EXCLUSIVE MODE');

            // Get next sequence number
            $count = static::withTrashed()
                ->whereDate('created_at', now()->toDateString())
                ->count() + 1;

            $code = $prefix . $date . str_pad($count, 4, '0', STR_PAD_LEFT);

            // Double check setelah lock
            while (static::withTrashed()->where('code', $code)->exists()) {
                $count++;
                $code = $prefix . $date . str_pad($count, 4, '0', STR_PAD_LEFT);
            }

            return trim($code);
        });
    }
}
