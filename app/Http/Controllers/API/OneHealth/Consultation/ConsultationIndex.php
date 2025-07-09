<?php

namespace App\Http\Controllers\API\OneHealth\Consultation;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Encounter\UpdateCreateEncounterCondition;
use App\Models\Company\Company;
use App\Models\Encounter\Encounter;
use App\Models\Transaction\Transaction;
use App\Services\OneHealth\Encounter\EncounterConditionService;
use App\Traits\Encryption;
use DB;
use Exception;
use Illuminate\Support\Facades\Request;
use Log;
use Throwable;

class ConsultationIndex extends BaseController
{
    use Encryption;

    // public function postPutConsultation(Request $request)
    // {
    //     dd($request);

    //     return response()->json($request);
    // }

    public function postPutConsultation(UpdateCreateEncounterCondition $request)
    {
        Log::info('postPutConsultation', [
            'request' => $request->all()
        ]);

        $encounter = Encounter::find($request?->id);
        if ($request?->id && !$encounter) {
            return $this->sendError('Data kunjungan tidak ditemukan', [
                'encounter_id' => $request?->encounter_id
            ], 404);
        }

        $transaction = Transaction::find($request?->transaction_id);
        if (!$request?->transaction_id || !$transaction) {
            return $this->sendError('Data Transaksi tidak ditemukan', [
                'transaction_id' => $request?->transaction_id
            ], 404);
        }

        $company = Company::find($request?->company_id);
        if (!$request?->company_id || !$company) {
            return $this->sendError('Data Perusahaan tidak ditemukan', [
                'company_id' => $request?->company_id
            ], 404);
        }

        try {
            DB::beginTransaction();

            $encounter = app(EncounterConditionService::class)->updateOrCreateEncounterCondition($request);
            if (!$encounter) {
                throw new \Exception("Ada kesalahan saat menjalankan EncounterConditionService::updateOrCreateEncounterCondition", 500);
            }

            $OHEncounter = app(EncounterConditionService::class)->updateOrCreateOHEncounterCondition($encounter);
            if (!$OHEncounter) {
                throw new \Exception("Ada kesalahan saat menjalankan EncounterConditionService::updateOrCreateOHEncounterCondition", 500);
            }

            $responseBody = [
                'encounter_id' => $encounter?->id,
                'encounter_oh_id' => $OHEncounter?->id,
                'encounter_oh_status' => $OHEncounter?->status,
            ];

            // Jika nanti ingin menggunakan service tambahan
            // $responseBody = app(EncounterConditionService::class)->postPutEncounterCondition($request, $encounter, $OHEncounter);

            DB::commit();
        } catch (\Exception | \Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada kesalahan saat postPutEncounterCondition', $error);
            return $this->sendError('Ada kesalahan saat postPutEncounterCondition', $error, 500);
        }

        return $this->sendResponse('Successfully postPutEncounterCondition', $responseBody);
    }
}
