<?php

namespace App\Imports\Product;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StockProductImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        Session::put('importStockProducts', $rows->toArray());
    }
}
