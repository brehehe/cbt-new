<?php

namespace App\Models\StockOpname;

use App\Models\Branch\Branch;
use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'date',
        'approved_at' => 'datetime',
        'total_loss_value' => 'decimal:2',
        'total_excess_value' => 'decimal:2',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function stockOpnameItems() {
        return $this->hasMany(StockOpnameItem::class, 'stock_opname_id');
    }

    public function approvedBy() {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('code', 'like', "%{$search}%")
                ->orWhere('date', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('branch', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('approvedBy', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('company', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
        });
    }
}
