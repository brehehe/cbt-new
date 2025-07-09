<?php

use App\Http\Controllers\API\OneHealth\Auth\AuthController;
use App\Http\Controllers\API\OneHealth\Consultation\ConsultationIndex;
use App\Http\Controllers\API\OneHealth\Deployment\PatientController;
use App\Http\Controllers\API\OneHealth\MasterData\RegionController;
use App\Http\Controllers\API\Onehealth\Organization\OrganizationController;
use App\Http\Controllers\API\OneHealth\OutPatient\EncounterController;
use App\Http\Controllers\API\TestingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/access-token', [AuthController::class, 'accessToken']);

Route::prefix('/master-data')->group(function () {
    Route::get('/province', [RegionController::class, 'getProvince']);
    Route::get('/city', [RegionController::class, 'getCity']);
    Route::get('/district', [RegionController::class, 'getDistrict']);
    Route::get('/sub-district', [RegionController::class, 'getSubDistrist']);
});

Route::prefix('/deployment')->group(function () {
    Route::post('/create-patient', [PatientController::class, 'createPatient']);
    Route::get('/get-patient/{patient_id}', [PatientController::class, 'getPatient']);
});

Route::prefix('/out-patient')->group(function () {
    Route::post('/create-encounter', [EncounterController::class, 'createEncounter']);
});

Route::prefix('organization')->group(function () {
    Route::post('/create', [OrganizationController::class, 'createOrganization']);
});

Route::prefix('/testing')->group(function () {
    Route::prefix('/company')->group(function () {
        Route::post('/post-put', [TestingController::class, 'postPutCompany']);
        Route::get('/get', [TestingController::class, 'getCompany']);
    });
    Route::prefix('/location')->group(function () {
        Route::post('/post-put', [TestingController::class, 'postPutLocation']);
    });

    Route::prefix('/practitiont')->group(function () {
        Route::get('/get-by-nik', [TestingController::class, 'getPractitiont']);
    });

    Route::prefix('/patient')->group(function () {
        Route::post('/post-put', [TestingController::class, 'postPutPatient']);
        Route::get('/get-nik', [TestingController::class, 'getPatient']);
    });

    Route::prefix('/encounter')->group(function () {
        Route::post('/post-put', [TestingController::class, 'postPutEncounter']);
    });

    Route::prefix('/condition')->group(function () {
        Route::post('/post-put', [ConsultationIndex::class, 'postPutConsultation']);
        Route::post('/postput', [TestingController::class, 'postPutCondition']);
    });

    Route::prefix('/medication')->group(function () {
        Route::post('/post-put', [TestingController::class, 'postPutMedication']);
    });

    Route::prefix('/medication-request')->group(function () {
        Route::post('/post-put', [TestingController::class, 'postPutMedicationRequest']);
    });

    Route::prefix('/medication-dispense')->group(function () {
        Route::post('/post-put', [TestingController::class, 'postPutMedicationDispense']);
    });
});
