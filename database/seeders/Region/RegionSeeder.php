<?php

namespace Database\Seeders\Region;

use App\Http\Controllers\API\OneHealth\Auth\AuthController;
use App\Http\Controllers\API\OneHealth\MasterData\RegionController;
use App\Models\Master\Region\City;
use App\Models\Master\Region\District;
use App\Models\Master\Region\Province;
use App\Models\Master\Region\SubDistrict;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RegionSeeder extends Seeder
{
    public $url;

    public function __construct()
    {
        $this->url = config('app.one_health.url');
    }

    public function run(): void
    {
        $access_token = Cache::get('accessToken');

        if (empty($access_token)) {
            Log::warning('Access token not found. Generating new token...');
            (new AuthController)->accessToken();
            $access_token = Cache::get('accessToken');
        }

        // Helper fetch data
        $fetchData = function ($url, $params = []) use (&$access_token) {
            for ($attempt = 0; $attempt < 3; $attempt++) {
                Log::info("Fetching from API", ['url' => $url, 'params' => $params, 'attempt' => $attempt + 1]);

                $response = Http::withToken($access_token['access_token'] ?? '')
                    ->withOptions(['verify' => false])
                    ->get($url, $params);

                if ($response->unauthorized()) {
                    Log::warning('Unauthorized response. Regenerating token...');
                    (new AuthController)->accessToken();
                    $access_token = Cache::get('accessToken');
                    continue;
                }

                if (!$response->successful()) {
                    Log::error('Request failed', [
                        'url' => $url,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    continue;
                }

                $body = json_decode($response->body(), true);
                $data = $body['data'] ?? [];

                if (!empty($data)) {
                    return $data;
                }

                Log::info('Empty data response', ['url' => $url]);
            }

            return [];
        };

        // Fetch provinces
        $provinces = $fetchData($this->url . "/masterdata/v1/provinces");
        Log::info("Total provinces fetched: " . count($provinces));

        foreach ($provinces as $province) {
            Province::updateOrCreate(
                ['code' => $province['code']],
                [
                    'parent_code' => $province['parent_code'],
                    'bps_code'    => $province['bps_code'],
                    'name'        => $province['name'],
                ]
            );

            $cities = $fetchData($this->url . "/masterdata/v1/cities", [
                'province_codes' => $province['code'],
            ]);
            Log::info("Total cities for province {$province['code']}: " . count($cities));

            foreach ($cities as $city) {
                City::updateOrCreate(
                    [
                        'code'        => $city['code'],
                        'parent_code' => $city['parent_code'],
                    ],
                    [
                        'bps_code'    => $city['bps_code'],
                        'name'        => $city['name'],
                    ]
                );

                $districts = $fetchData($this->url . "/masterdata/v1/districts", [
                    'city_codes' => $city['code'],
                ]);
                Log::info("Total districts for city {$city['code']}: " . count($districts));

                foreach ($districts as $district) {
                    District::updateOrCreate(
                        [
                            'code'        => $district['code'],
                            'parent_code' => $district['parent_code'],
                        ],
                        [
                            'bps_code'    => $district['bps_code'],
                            'name'        => $district['name'],
                        ]
                    );

                    $sub_districts = $fetchData($this->url . "/masterdata/v1/sub-districts", [
                        'district_codes' => $district['code'],
                    ]);
                    Log::info("Total sub-districts for district {$district['code']}: " . count($sub_districts));

                    foreach ($sub_districts as $sub_district) {
                        SubDistrict::updateOrCreate(
                            [
                                'code'        => $sub_district['code'],
                                'parent_code' => $sub_district['parent_code'],
                            ],
                            [
                                'bps_code'    => $sub_district['bps_code'],
                                'name'        => $sub_district['name'],
                            ]
                        );
                    }
                }
            }
        }

        Log::info("Data sync selesai.");
    }

}
