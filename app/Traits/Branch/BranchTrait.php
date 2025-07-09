<?php

namespace App\Traits\Branch;

use App\Models\Branch\Branch;

trait BranchTrait
{
    //
    public function getBranch()
    {
        return Branch::where('company_id', auth()->user()->company_id)->orderBy('order','desc')->get();
    }

    public function getBranchOne() {
        return Branch::where('company_id', auth()->user()->company_id)->first();
    }
}
