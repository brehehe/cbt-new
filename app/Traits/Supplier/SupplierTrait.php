<?php

namespace App\Traits\Supplier;

use App\Models\Supplier\Supplier;
use Illuminate\Support\Facades\Auth;

trait SupplierTrait
{
    //
    public function getSuppliers()
    {
        return Supplier::select('id', 'name')->orderBy('name', 'asc')->where('company_id', Auth::user()->company_id)->get()->toArray();
    }
}
