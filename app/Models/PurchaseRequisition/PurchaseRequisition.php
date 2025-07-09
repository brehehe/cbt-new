<?php

namespace App\Models\PurchaseRequisition;

use App\Models\Company\Company;
use App\Models\PurchaseOrder\PurchaseOrder;
use App\Models\Supplier\Supplier;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequisition extends Model
{
    //
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function purchaseRequisitionItems()
    {
        return $this->hasMany(PurchaseRequisitionItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where(function ($query) use ($search) {
                $query->where('number', 'ilike', '%'.$search.'%')
                    ->orWhere('status', 'ilike', '%'.$search.'%')
                    ->orWhere('grand_total', 'ilike', '%'.$search.'%')
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('name', 'ilike', '%'.$search.'%');
                    })
                    ->orWhereHas('purchaseOrder', function ($q) use ($search) {
                        $q->where('number', 'ilike', '%'.$search.'%');
                    })
                    ->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('name', 'ilike', '%'.$search.'%');
                    });
            });
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            // Set order
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;

            // Set number
            $today = now()->format('Ymd');
            $lastNumber = static::whereDate('created_at', now()->toDateString())
                ->where('number', 'ilike', 'SP'.$today.'%')
                ->orderBy('number', 'desc')
                ->value('number');

            if ($lastNumber) {
                $lastIncrement = (int) substr($lastNumber, -4); // Ambil 4 digit terakhir
                $newIncrement = str_pad($lastIncrement + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newIncrement = '0001';
            }

            $modelCreate->number = 'SP'.$today.$newIncrement;
        });
    }
}
