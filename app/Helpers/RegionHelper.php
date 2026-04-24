<?php

namespace App\Helpers;

use App\Http\Controllers\API\OneHealth\Auth\AuthController;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class RegionHelper
{
    public static function province($datas = [])
    {
        $url = config('app.one_health.url');

        try {
            $access_token = Cache::get('accessToken');
            $response = Http::withToken(isset($access_token['access_token']) ? $access_token['access_token'] : '')->get($url.'/masterdata/v1/provinces');

            if ($response->unauthorized() || empty(Cache::get('accessToken'))) {
                (new AuthController)->accessToken();
                $access_token = Cache::get('accessToken');
                $response = Http::withToken(isset($access_token['access_token']) ? $access_token['access_token'] : '')->get($url.'/masterdata/v1/provinces');
            }

            if ($response->failed()) {
                throw new Exception(json_encode($response->json()), 500);
            }

        } catch (Exception|Throwable $th) {
            $error = [
                'error_message' => $th->getMessage(),
            ];
            Log::error('Ada kesalahan saat Get Province', $error);

            return AlertHelper::error('Gagal!', 'Ada kesalahan saat Get Province');
        }

        return $datas = json_decode($response->body(), true)['data'];
    }
}
