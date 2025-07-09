<?php

namespace App\Http\Controllers\API\OneHealth\MasterData;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\API\OneHealth\Auth\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Master\Region\City;
use App\Models\Master\Region\District;
use App\Models\Master\Region\Province;
use App\Models\Master\Region\SubDistrict;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class RegionController extends BaseController
{
    //
    public $url;

    public function __construct()
    {
        $this->url = config('app.one_health.url');
    }

    public function getProvince()
    {
        try {
            $access_token = Cache::get('accessToken');
            $response = Http::withToken(isset($access_token['access_token']) ? $access_token['access_token'] : '')
            ->withOptions(['verify' => false])
            ->get($this->url ."/masterdata/v1/provinces");

            if ($response->unauthorized() || empty(Cache::get('accessToken'))) {
                (new AuthController)->accessToken();
                $access_token = Cache::get('accessToken');
                $response = Http::withToken(isset($access_token['access_token']) ? $access_token['access_token'] : '')
                    ->withOptions(['verify' => false])
                    ->get($this->url ."/masterdata/v1/provinces");
            }

            if ($response->failed()) {
                throw new Exception(json_encode($response->json()), 500);
            }

        } catch (Exception | Throwable $th) {
            $error = array(
                'error_message' => $th->getMessage()
            );
            Log::error("Ada kesalahan saat Get Province", $error);
            return $this->sendError("Ada kesalahan saat Get Province", $error, 500);
        }

        $data = json_decode($response->body(), true)['data'];

        if (isset($data)) {
            try {
                DB::beginTransaction();
                    foreach ($data as $key => $value) {
                        Province::updateOrCreate(
                            [
                                'code' => $value['code'],
                            ],
                            [
                                'parent_code' => $value['parent_code'],
                                'bps_code'    => $value['bps_code'],
                                'name'        => $value['name'],
                            ]
                        );
                    }
                DB::commit();
            } catch (Exception | Throwable $th) {
                DB::rollBack();
                $error = array(
                    'error_message' => $th->getMessage()
                );
                Log::error("Ada kesalahan saat set data province", $error);
                return $this->sendError("Ada kesalahan saat set data province", $error, 500);
            }
            return $this->sendResponse($data, "Data Province $this->found_msg");
        } else {
            return $this->sendError("Data Province $this->notfound_msg");
        }
    }

    public function getCity(Request $request)
    {
        $rules = [
            'province_codes' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError('Input tidak sesuai dengan ketentuan.', $validator->errors(), 400);
        }

        try {

            $access_token = Cache::get('accessToken');
            $response = Http::withToken(isset($access_token['access_token']) ? $access_token['access_token'] : '')
            ->withOptions(['verify' => false])
            ->get($this->url .'/masterdata/v1/cities', [
                'province_codes' => (string)$request?->province_codes
            ]);

            if ($response->unauthorized() || empty(Cache::get('accessToken'))) {
                (new AuthController)->accessToken();
                $access_token = Cache::get('accessToken');
                $response     = Http::withToken(isset($access_token['access_token']) ? $access_token['access_token'] : '')
                ->withOptions(['verify' => false])
                ->get($this->url .'/masterdata/v1/cities', [
                    'province_codes' => (string)$request?->province_codes
                ]);
            }

            if ($response->failed()) {
                throw new Exception(json_encode($response->json()), 500);
            }

        } catch (Exception | Throwable $th) {
            $error = array(
                'error_message' => $th->getMessage()
            );
            Log::error("Ada kesalahan saat Get City", $error);
            return $this->sendError("Ada kesalahan saat Get City", $error, 500);
        }

        $data = json_decode($response->body(), true)['data'];

        if (isset($data)) {
            try {
                DB::beginTransaction();
                    foreach ($data as $key => $value) {
                        City::updateOrCreate(
                            [
                                'code'        => $value['code'],
                                'parent_code' => $value['parent_code'],
                            ],
                            [
                                'bps_code'    => $value['bps_code'],
                                'name'        => $value['name'],
                            ]
                        );
                    }
                DB::commit();
            } catch (Exception | Throwable $th) {
                DB::rollBack();
                $error = array(
                    'error_message' => $th->getMessage()
                );
                Log::error("Ada kesalahan saat set data city", $error);
                return $this->sendError("Ada kesalahan saat set data city", $error, 500);
            }
            return $this->sendResponse($data, "Data City $this->found_msg");
        } else {
            return $this->sendError("Data City $this->notfound_msg");
        }
    }

    public function getDistrict(Request $request)
    {
        $rules = [
            'city_codes' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError('Input tidak sesuai dengan ketentuan.', $validator->errors(), 400);
        }

        try {
            $access_token = Cache::get('accessToken');
            $response = Http::withToken(isset($access_token['access_token']) ? $access_token['access_token'] : '')
            ->withOptions(['verify' => false])
            ->get($this->url .'/masterdata/v1/districts', [
                'city_codes' => (string)$request?->city_codes
            ]);

            if ($response->unauthorized() || empty(Cache::get('accessToken'))) {
                (new AuthController)->accessToken();
                $access_token = Cache::get('accessToken');
                $response     = Http::withToken(isset($access_token['access_token']) ? $access_token['access_token'] : '')
                ->withOptions(['verify' => false])
                ->get($this->url .'/masterdata/v1/districts', [
                    'city_codes' => (string)$request?->city_codes
                ]);
            }

            if ($response->failed()) {
                throw new Exception(json_encode($response->json()), 500);
            }

        } catch (Exception | Throwable $th) {
            $error = array(
                'error_message' => $th->getMessage()
            );
            Log::error("Ada kesalahan saat Get District", $error);
            return $this->sendError("Ada kesalahan saat Get District", $error, 500);
        }

        $data = json_decode($response->body(), true)['data'];

        if (isset($data)) {
            try {
                DB::beginTransaction();
                    foreach ($data as $key => $value) {
                        District::updateOrCreate(
                            [
                                'code'        => $value['code'],
                                'parent_code' => $value['parent_code'],
                            ],
                            [
                                'bps_code'    => $value['bps_code'],
                                'name'        => $value['name'],
                            ]
                        );
                    }
                DB::commit();
            } catch (Exception | Throwable $th) {
                DB::rollBack();
                $error = array(
                    'error_message' => $th->getMessage()
                );
                Log::error("Ada kesalahan saat set data district", $error);
                return $this->sendError("Ada kesalahan saat set data district", $error, 500);
            }
            return $this->sendResponse($data, "Data District $this->found_msg");
        } else {
            return $this->sendError("Data District $this->notfound_msg");
        }
    }

    public function getSubDistrist(Request $request)
    {
        $rules = [
            'district_codes' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError('Input tidak sesuai dengan ketentuan.', $validator->errors(), 400);
        }

        try {
            $access_token = Cache::get('accessToken');
            $response = Http::withToken(isset($access_token['access_token']) ? $access_token['access_token'] : '')
            ->withOptions(['verify' => false])
            ->get($this->url .'/masterdata/v1/sub-districts', [
                'district_codes' => (string)$request?->district_codes
            ]);

            if ($response->unauthorized() || empty(Cache::get('accessToken'))) {
                (new AuthController)->accessToken();
                $access_token = Cache::get('accessToken');
                $response     = Http::withToken(isset($access_token['access_token']) ? $access_token['access_token'] : '')
                ->withOptions(['verify' => false])
                ->get($this->url .'/masterdata/v1/sub-districts', [
                    'district_codes' => (string)$request?->district_codes
                ]);
            }

            if ($response->failed()) {
                throw new Exception(json_encode($response->json()), 500);
            }

        } catch (Exception | Throwable $th) {
            $error = array(
                'error_message' => $th->getMessage()
            );
            Log::error("Ada kesalahan saat Get Sub District", $error);
            return $this->sendError("Ada kesalahan saat Get Sub District", $error, 500);
        }

        $data = json_decode($response->body(), true)['data'];

        if (isset($data)) {
            try {
                DB::beginTransaction();
                    foreach ($data as $key => $value) {
                        SubDistrict::updateOrCreate(
                            [
                                'code'        => $value['code'],
                                'parent_code' => $value['parent_code'],
                            ],
                            [
                                'bps_code'    => $value['bps_code'],
                                'name'        => $value['name'],
                            ]
                        );
                    }
                DB::commit();
            } catch (Exception | Throwable $th) {
                DB::rollBack();
                $error = array(
                    'error_message' => $th->getMessage()
                );
                Log::error("Ada kesalahan saat set data sub district", $error);
                return $this->sendError("Ada kesalahan saat set data sub district", $error, 500);
            }
            return $this->sendResponse($data, "Data Sub District $this->found_msg");
        } else {
            return $this->sendError("Data Sub District $this->notfound_msg");
        }
    }
}
