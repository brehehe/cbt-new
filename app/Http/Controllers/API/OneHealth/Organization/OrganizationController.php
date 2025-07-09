<?php

namespace App\Http\Controllers\API\OneHealth\Organization;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Services\Onehealth\Organizaion\OneHealthOrganizationService as Onehealth_Organizaion_OrganizationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrganizationController extends BaseController
{
    //
    public $oneHealthOrganizationService, $systemOrganizationService;

    public function __construct()
    {
        // $this->systemOrganizationService    = new System_OutPatient_EncounterService();
        $this->oneHealthOrganizationService = new Onehealth_Organizaion_OrganizationService();
    }

    public function createOrganization(Request $request)
    {
        try {

            Company::find('019714d1-41a3-705b-b7eb-6d44e21b1975')->update([
                'code' => '2P5m9n'
            ]);

        } catch (Exception | Throwable $th) {
             $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat createOrganization', $error);
            return $this->sendError('Ada Kesalahaan saat createOrganization', $error, 500);
        }
    }
}
