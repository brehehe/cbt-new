<?php

namespace Database\Seeders\Region;

use App\Http\Controllers\API\OneHealth\Auth\AuthController;
use App\Models\Master\Region\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProvinceSeeder extends Seeder
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

        $provinces = $this->fetchData("/masterdata/v1/provinces");

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
        }

        Log::info("Provinces seeding finished.");
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
