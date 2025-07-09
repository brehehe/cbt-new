<?php

namespace Database\Seeders\Region;

use App\Http\Controllers\API\OneHealth\Auth\AuthController;
use App\Models\Master\Region\City;
use App\Models\Master\Region\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CitySeeder extends Seeder
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

        $provinces = Province::all();

        foreach ($provinces as $province) {
            $cities = $this->fetchData("/masterdata/v1/cities", [
                'province_codes' => $province->code,
            ]);
            Log::info("Total cities for province {$province->code}: " . count($cities));

            foreach ($cities as $city) {
                City::updateOrCreate(
                    ['code' => $city['code']],
                    [
                        'parent_code' => $city['parent_code'],
                        'bps_code'    => $city['bps_code'],
                        'name'        => $city['name'],
                    ]
                );
            }
            sleep(2);
        }

        Log::info("Cities seeding finished.");
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
