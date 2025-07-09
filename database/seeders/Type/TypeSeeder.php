<?php

namespace Database\Seeders\Type;

use App\Models\Product\ProductType;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $types = [
            ['name' => 'Obat'],
            ['name' => 'Vaksin'],
            ['name' => 'Tindakan'],
            ['name' => 'Produk Pendukung'],
            ['name' => 'Paket'],
            ['name' => 'Resep'],
        ];

        foreach ($types as $type) {
            ProductType::create($type);
        }
    }
}
