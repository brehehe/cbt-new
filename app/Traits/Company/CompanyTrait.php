<?php

namespace App\Traits\Company;

use Illuminate\Support\Facades\Crypt;

trait CompanyTrait
{
    //
    public function getOneHealth($company)
    {
        $one_healthy = $organization_id = $client_id = $client_secret = null;

        if (! $company) {
            return [$organization_id, $client_id, $client_secret];
        }

        $one_healthy = $this->getCompanyHasOneHealth($company)?->oneHealthy;

        if (! $one_healthy) {
            return [$organization_id, $client_id, $client_secret];
        }

        $organization_id = Crypt::decryptString($one_healthy?->organization_id);
        $client_id = Crypt::decryptString($one_healthy?->client_id);
        $client_secret = Crypt::decryptString($one_healthy?->client_secret);

        return [$organization_id, $client_id, $client_secret];
    }

    public function getCompanyHasOneHealth($company)
    {
        do {
            if ($company?->oneHealthy) {
                break;
            }

            $company = $company?->company;

        } while ($company != null);

        return $company;
    }
}
