<?php

namespace App\Models\PurchaseOrder;

use App\Models\Company\Company;
use App\Models\PurchaseRequisition\PurchaseRequisition;
use App\Models\Supplier\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    //
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function purchaseRequisition() {
        return $this->hasOne(PurchaseRequisition::class);
    }

    public function purchaseOrderItems() {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function company() {
        return $this->belongsTo(Company::class);
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
