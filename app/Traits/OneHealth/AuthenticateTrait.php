<?php

namespace App\Traits\OneHealth;

use App\Traits\Company\CompanyTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Throwable;

trait AuthenticateTrait
{
    //
    use CompanyTrait;

    public function accessToken($company)
    {
        $url = config('app.one_health.url');

        $company_has_one_health = $this->getCompanyHasOneHealth($company);

        $client_id     = $company_has_one_health?->oneHealthy?->client_id ? Crypt::decryptString($company_has_one_health?->oneHealthy?->client_id) : $company_has_one_health?->oneHealthy?->client_id;
        $client_secret = $company_has_one_health?->oneHealthy?->client_secret ? Crypt::decryptString($company_has_one_health?->oneHealthy?->client_secret) : $company_has_one_health?->oneHealthy?->client_secret;

        try {
            $response = Http::asForm()
                ->withOptions(['verify' => false])
                ->post($url .'/oauth2/v1/accesstoken?grant_type=client_credentials', [
                    'client_id'     => $client_id,
                    'client_secret' => $client_secret,
                ]);

            $responseBody = $response->json();
            if ($response->failed()) {
                $message      = $responseBody['message'] ?? json_encode($responseBody);
                throw new Exception($message, 500);
            }

            while (DB::transactionLevel() > 0) {
                DB::commit();
            }

            // dd($company, $responseBody['access_token']);
                // Update using raw DB query to avoid triggering Company model events
                DB::table('companies')
                    ->where('id', $company->id)
                    ->update([
                        'one_health_access_token' => $responseBody['access_token'],
                        // 'one_health_access_token' => isset($responseBody['access_token']) ? Crypt::encrypt($responseBody['access_token']) : null,
                        'updated_at' => now()
                    ]);
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error("Ada kesalahan saat Access Token", $error);
            return null;
        }
        return $responseBody['access_token'];
    }
}
