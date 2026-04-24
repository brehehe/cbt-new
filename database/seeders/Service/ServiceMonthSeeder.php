<?php

namespace Database\Seeders\Service;

use App\Models\Service\Service;
use App\Models\Service\ServiceMonth;
use App\Models\Service\ServiceMonthDetail;
use Illuminate\Database\Seeder;

class ServiceMonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $serviceMonths = [
            [
                'name' => '14 (Trial)',
                'description' => '14 Hari (Trial)',
                'duration_days' => 14,
                'price' => 0,
                'is_trial' => true,
                'is_active' => true,
                'serviceMonthDetails' => [
                    [
                        'service_id' => Service::where('name', 'Rawat Inap')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Rapat Jalan')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Laboratorium')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Farmasi')->first()->id,
                    ],
                ],
            ],
            [
                'name' => '1 Bulan',
                'description' => '1 Bulan',
                'duration_days' => 30,
                'price' => 100000,
                'is_trial' => false,
                'is_active' => true,
                'serviceMonthDetails' => [
                    [
                        'service_id' => Service::where('name', 'Rawat Inap')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Rapat Jalan')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Laboratorium')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Farmasi')->first()->id,
                    ],
                ],
            ],
            [
                'name' => '3 Bulan',
                'description' => '3 Bulan',
                'duration_days' => 90,
                'price' => 200000,
                'is_trial' => false,
                'is_active' => true,
                'serviceMonthDetails' => [
                    [
                        'service_id' => Service::where('name', 'Rawat Inap')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Rapat Jalan')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Laboratorium')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Farmasi')->first()->id,
                    ],
                ],
            ],
            [
                'name' => '6 Bulan',
                'description' => '6 Bulan',
                'duration_days' => 180,
                'price' => 300000,
                'is_trial' => false,
                'is_active' => true,
                'serviceMonthDetails' => [
                    [
                        'service_id' => Service::where('name', 'Rawat Inap')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Rapat Jalan')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Laboratorium')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Farmasi')->first()->id,
                    ],
                ],
            ],
            [
                'name' => '1 Tahun',
                'description' => '1 Tahun',
                'duration_days' => 365,
                'price' => 500000,
                'is_trial' => false,
                'is_active' => true,
                'serviceMonthDetails' => [
                    [
                        'service_id' => Service::where('name', 'Rawat Inap')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Rapat Jalan')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Laboratorium')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Farmasi')->first()->id,
                    ],
                ],
            ],
            [
                'name' => 'Lifetime',
                'description' => 'Lifetime',
                'duration_days' => 0,
                'price' => 0,
                'is_trial' => false,
                'is_lifetime' => true,
                'is_active' => true,
                'serviceMonthDetails' => [
                    [
                        'service_id' => Service::where('name', 'Rawat Inap')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Rapat Jalan')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Laboratorium')->first()->id,
                    ],
                    [
                        'service_id' => Service::where('name', 'Farmasi')->first()->id,
                    ],
                ],
            ],
        ];

        foreach ($serviceMonths as $serviceMonth) {
            $serviceMonthDetail = ServiceMonth::create([
                'name' => $serviceMonth['name'],
                'description' => $serviceMonth['description'],
                'duration_days' => $serviceMonth['duration_days'],
                'price' => $serviceMonth['price'],
                'is_trial' => $serviceMonth['is_trial'],
                'is_lifetime' => $serviceMonth['is_lifetime'] ?? false,
                'is_active' => $serviceMonth['is_active'],
            ]);

            foreach ($serviceMonth['serviceMonthDetails'] as $detail) {
                $detail['service_month_id'] = $serviceMonthDetail->id;
                ServiceMonthDetail::create($detail);
            }
        }
    }
}
