<?php

namespace App\Models\Company\OneHealth;

use App\Models\Company\Company;
use App\Models\Encounter\OneHealth\OneHealthEncounter;
use App\Models\Location\OneHealth\OneHealthLocation;
use App\Models\Master\CodeSystem\Organization\MasterOrganizationType;
use App\Models\Master\CodeSystem\Organization\OrganizationType;
use App\Models\Medication\OneHealth\OneHealthMedicationIdentifier;
use App\Models\MedicationDispense\OneHealth\OneHealthMedicationDispense;
use App\Models\MedicationDispense\OneHealth\OneHealthMedicationDispenseIdentifier;
use App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestDispenseRequest;
use App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestIdentifier;
use App\Services\OneHealth\Organizaion\OneHealthOrganizationService;
use Exception;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class OneHealthOrganization extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

            static::creating(function ($modelCreate) {
                $lastOrder = static::max('order');
                $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            });

            static::saved(function ($model) {
                try {
                    if ($model->wasChanged(['organization_id']) && count($model->getChanges()) === 2) {
                        return;
                    }

                    // Force commit any pending transactions before proceeding
                    $initialTransactionLevel = DB::transactionLevel();
                    if ($initialTransactionLevel > 0) {

                        while (DB::transactionLevel() > 0) {
                            DB::commit();
                        }
                    }
                    // dd('OH');
                    // app(OneHealthOrganizationService::class)->postPutOrganization($model);
                } catch (Exception | Throwable $th) {
                    DB::rollBack();
                    $error = [
                        'message' => $th->getMessage(),
                        'file'    => $th->getFile(),
                        'line'    => $th->getLine(),
                    ];

                    Log::error('Ada kesalahan saat boot OneHealthOrganization', $error);
                }
            });
    }

    /**
     * Get the company that owns the OneHealthOrganization
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * Get the OHOrganizationIdentifier associated with the OneHealthOrganization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHOrganizationIdentifier(): HasOne
    {
        return $this->hasOne(OneHealthOrganizationIdentifier::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get all of the OHOrganizationTelecoms for the OneHealthOrganization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHOrganizationTelecoms(): HasMany
    {
        return $this->hasMany(OneHealthOrganizationTelecom::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get the organizationtype associated with the OneHealthOrganization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHOrganizationAddress(): HasOne
    {
        return $this->hasOne(OneHealthOrganizationAddress::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get the user that owns the OneHealthOrganization
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function typeCodingCode(): BelongsTo
    {
        return $this->belongsTo(MasterOrganizationType::class, 'type_coding_code', 'code');
    }

    /**
     * Get all of the OHLocations for the OneHealthOrganization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHLocations(): HasMany
    {
        return $this->hasMany(OneHealthLocation::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get all of the OHEncounter for the OneHealthOrganization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHEncounter(): HasMany
    {
        return $this->hasMany(OneHealthEncounter::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get all of the OHMedicationIdentifiers for the OneHealthOrganization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationIdentifiers(): HasMany
    {
        return $this->hasMany(OneHealthMedicationIdentifier::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get all of the OHMedicationReqIndentifiers for the OneHealthOrganization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationReqIdentifiers(): HasMany
    {
        return $this->hasMany(OneHealthMedicationRequestIdentifier::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get all of the OHMedicationReqDispenseRequest for the OneHealthOrganization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationReqDispenseRequest(): HasMany
    {
        return $this->hasMany(OneHealthMedicationRequestDispenseRequest::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get all of the OHMedicationReqs for the OneHealthOrganization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationReqs(): HasMany
    {
        return $this->hasMany(OneHealthOrganization::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get all of the OHMedicationDispenses for the OneHealthOrganization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationDispenses(): HasMany
    {
        return $this->hasMany(OneHealthMedicationDispense::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get all of the OHMedicationDispenseIdentifiers for the OneHealthOrganization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationDispenseIdentifiers(): HasMany
    {
        return $this->hasMany(OneHealthMedicationDispenseIdentifier::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get all of the OHCondition for the OneHealthOrganization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHConditions(): HasMany
    {
        return $this->hasMany(OneHealthOrganization::class, 'one_health_organization_id', 'id');
    }
}
