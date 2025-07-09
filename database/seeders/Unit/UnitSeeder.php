<?php

namespace Database\Seeders\Unit;

use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestOrderableDrugForm;
use App\Models\Unit\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            // Satuan Berat
            ['code' => 'Kilogram', 'description' => 'Satuan untuk mengukur berat (kg)'],
            ['code' => 'Gram', 'description' => 'Satuan kecil untuk berat (g)'],
            ['code' => 'Miligram', 'description' => 'Satuan sangat kecil untuk berat (mg)'],

            // Satuan Volume
            ['code' => 'Liter', 'description' => 'Satuan untuk mengukur volume cairan (L)'],
            ['code' => 'Mililiter', 'description' => 'Satuan kecil untuk volume cairan (mL)'],

            // Satuan Panjang
            ['code' => 'Meter', 'description' => 'Satuan untuk mengukur panjang (m)'],
            ['code' => 'Sentimeter', 'description' => 'Satuan kecil untuk panjang (cm)'],
            ['code' => 'Milimeter', 'description' => 'Satuan lebih kecil untuk panjang (mm)'],

            // Satuan Jumlah
            ['code' => 'Pcs', 'description' => 'Satuan umum untuk barang dalam jumlah satuan (pieces)'],
            ['code' => 'Lembar', 'description' => 'Satuan untuk benda tipis seperti kertas atau kain'],
            ['code' => 'Butir', 'description' => 'Satuan untuk benda kecil seperti obat atau telur'],
            ['code' => 'Pasang', 'description' => 'Satuan untuk dua benda yang berpasangan seperti sepatu'],

            // Satuan Pengemasan
            ['code' => 'Dus', 'description' => 'Satuan pengemasan dalam bentuk kotak atau kardus'],
            ['code' => 'Box', 'description' => 'Satuan pengemasan umum dalam bentuk kotak'],
            ['code' => 'Pack', 'description' => 'Satuan kemasan, biasanya berisi beberapa item'],
            ['code' => 'Sachet', 'description' => 'Satuan kecil dalam kemasan sekali pakai'],
            ['code' => 'Botol', 'description' => 'Satuan cairan dalam wadah botol'],
            ['code' => 'Kaleng', 'description' => 'Satuan dalam wadah kaleng'],
            ['code' => 'Tube', 'description' => 'Satuan dalam kemasan berbentuk tabung'],

            // Satuan Medis & Farmasi
            ['code' => 'Tablet', 'description' => 'Satuan padat untuk obat'],
            ['code' => 'Kapsul', 'description' => 'Satuan obat dalam bentuk kapsul'],
            ['code' => 'Strip', 'description' => 'Satuan kemasan beberapa tablet/kapsul'],
            ['code' => 'Ampul', 'description' => 'Satuan cairan suntik dalam wadah kaca kecil'],
            ['code' => 'Vial', 'description' => 'Satuan cairan suntik dalam wadah botol kecil'],
            ['code' => 'Syringe', 'description' => 'Satuan suntikan medis'],
            ['code' => 'Dosis', 'description' => 'Satuan pemberian obat berdasarkan takaran'],

            // Lain-lain
            ['code' => 'Kotak', 'description' => 'Satuan kemasan dalam bentuk kotak'],
            ['code' => 'Karung', 'description' => 'Satuan besar dalam bentuk karung (biasanya untuk bahan pokok)'],
            ['code' => 'Roll', 'description' => 'Satuan untuk barang yang digulung seperti kain atau kertas'],
            ['code' => 'Galon', 'description' => 'Satuan volume besar, biasanya untuk air minum'],
        ];

        foreach ($units as $unit) {
            $unit['name'] = $unit['code'];
            Unit::create($unit);
        }

        $master_medication_request_orderable_drug_forms = MasterMedicationRequestOrderableDrugForm::orderBy('code')
            ->select('code', 'display')
            ->get()
            ->pluck('display', 'code')->toArray();

        foreach ($master_medication_request_orderable_drug_forms as $code => $display) {
            Unit::create([
                'name' => $code,
                'code' => $code,
                'description' => "Unit for {$display}",
            ]);
        }
    }
}
