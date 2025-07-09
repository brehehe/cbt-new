<?php

namespace App\Http\Controllers\API\OneHealth\Auth;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\Company\OneHealthy;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthController extends BaseController
{
    //
    public $url, $organization_id, $client_id, $client_secret;

    public function __construct()
    {
        $this->url             = config('app.one_health.url');
        $this->organization_id = config('app.one_health.organization_id');
        $this->client_id       = config('app.one_health.client_id');
        $this->client_secret   = config('app.one_health.client_secret');
    }

    public function accessToken()
    {
        try {
            $response = Http::asForm()
            ->withOptions(['verify' => false])
            ->post($this->url .'/oauth2/v1/accesstoken?grant_type=client_credentials', [
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
            ]);

            $responseBody = $response->json();                                       // sudah array
            if ($response->failed()) {
                $message      = $responseBody['message'] ?? json_encode($responseBody);
                throw new Exception($message, 500);
            }

        } catch (Exception | Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error("Ada kesalahan saat Access Token", $error);
            return $this->sendError("Ada kesalahan saat Access Token", $error);
        }

        Cache::put('accessToken', json_decode($response->body(), true));

        return $this->sendResponse(json_decode($response->body(), true), "Access Token Successfully");
    }
}
