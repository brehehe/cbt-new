<?php

namespace Database\Seeders\Region;

use App\Http\Controllers\API\OneHealth\Auth\AuthController;
use App\Models\Master\Region\District;
use App\Models\Master\Region\SubDistrict;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SubDistrictSeeder extends Seeder
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

        $districts = District::all();

        foreach ($districts as $district) {
            $sub_districts = $this->fetchData("/masterdata/v1/sub-districts", [
                'district_codes' => $district->code,
            ]);
            Log::info("Total sub-districts for district {$district->code}: " . count($sub_districts));

            foreach ($sub_districts as $sub_district) {
                SubDistrict::updateOrCreate(
                    ['code' => $sub_district['code']],
                    [
                        'parent_code' => $sub_district['parent_code'],
                        'bps_code'    => $sub_district['bps_code'],
                        'name'        => $sub_district['name'],
                    ]
                );
            }
            sleep(2);
        }

        Log::info("Sub-districts seeding finished.");
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
