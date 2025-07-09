<?php

namespace Database\Seeders\Region;

use App\Http\Controllers\API\OneHealth\Auth\AuthController;
use App\Models\Master\Region\City;
use App\Models\Master\Region\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DistrictSeeder extends Seeder
{
    public $url;

    public function __construct()
    {
        $this->url = config('app.one_health.url');
    }

    public function run()
    {
        $access_token = Cache::get('accessToken');

        if (empty($access_token)) {
            Log::warning('Access token not found. Generating new token...');
            (new AuthController)->accessToken();
            $access_token = Cache::get('accessToken');
        }

        $cities = City::all();

        foreach ($cities as $city) {
            $districts = $this->fetchData("/masterdata/v1/districts", [
                'city_codes' => $city->code,
            ]);
            Log::info("Total districts for city {$city->code}: " . count($districts));

            foreach ($districts as $district) {
                District::updateOrCreate(
                    ['code' => $district['code']],
                    [
                        'parent_code' => $district['parent_code'],
                        'bps_code'    => $district['bps_code'],
                        'name'        => $district['name'],
                    ]
                );
            }
            sleep(2);
        }

        Log::info("Districts seeding finished.");
    }

    private function fetchData($endpoint, $params = [])
    {
        $access_token = Cache::get('accessToken');

        $response = Http::withToken($access_token['access_token'] ?? '')
            ->withOptions(['verify' => false])
            ->get($this->url . $endpoint, $params);

        if ($response->successful()) {
            return $response->json('data');
        }

        Log::error("Failed to fetch data from {$endpoint}", [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return [];
    }
}
