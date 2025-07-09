<?php

namespace App\Models\Location;

use Exception;
use Throwable;
use Illuminate\Support\Str;
use App\Models\Company\Company;
use Illuminate\Support\Facades\DB;
use App\Models\Encounter\Encounter;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Location\OneHealth\OneHealthLocation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Master\CodeSystem\Location\MasterLocationMode;
use App\Models\Master\CodeSystem\Location\MasterLocationType;
use App\Models\Master\CodeSystem\Location\MasterLocationStatus;
use App\Models\MedicationDispense\MedicationDispense;

class Location extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            // $modelCreate->company_id = $modelCreate->company_id ?? auth()->user()->company_id;
            $modelCreate->slug = Str::slug($modelCreate->name);
        });

        static::saved(function ($model) {
            try {
                // Force commit any pending transactions before proceeding
                $initialTransactionLevel = DB::transactionLevel();
                if ($initialTransactionLevel > 0) {

                    while (DB::transactionLevel() > 0) {
                        DB::commit();
                    }
                }

                $model->updateQuietly([
                    'slug' => Str::slug($model->name)
                ]);
                $model->updateOrCreateOHLocation();
            } catch (Exception | Throwable $th) {
                $error = [
                    'message' => $th->getMessage(),
                    'file'    => $th->getFile(),
                    'line'    => $th->getLine(),
                ];

                Log::error('Ada kesalahan saat boot Location', $error);
            }
        });
    }

    /**
     * Get the OHLocation associated with the Location
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHLocation(): HasOne
    {
        return $this->hasOne(OneHealthLocation::class, 'location_id', 'id');
    }

    /**
     * Get the location that owns the Location
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function updateOrCreateOHLocation()
    {
        $this->OHLocation()->updateOrCreate(
            [
                'location_id' => $this->id
            ],
            [
                'one_health_organization_id'      => $this->company?->OHOrganization?->id,
                'name'                            => $this->name,
                'description'                     => $this->description,
                'status'                          => $this->status,
                'mode'                            => $this->mode,
                'physicalType_coding_code'        => $this->physical_type,
                'managing_organization_reference' => $this->company?->OHOrganization?->id_organization,
                'part_of_reference'               => $this->location?->OHLocation?->id_location
            ]
        );
    }

    /**
     * Get the status that owns the OneHealthLocation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(MasterLocationStatus::class, 'status', 'code');
    }

    /**
     * Get the mode that owns the OneHealthLocation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mode(): BelongsTo
    {
        return $this->belongsTo(MasterLocationMode::class, 'mode', 'code');
    }

     /**
     * Get the mode that owns the OneHealthLocation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function physicalType(): BelongsTo
    {
        return $this->belongsTo(MasterLocationType::class, 'physical_type', 'code');
    }

    /**
     * Get all of the encounters for the Location
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function encounters(): HasMany
    {
        return $this->hasMany(Encounter::class, 'location_id', 'id');
    }

    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhere('slug', 'like', '%' . $search . '%');
        });
    }

    /**
     * Get all of the medicationDispenses for the Location
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicationDispenses(): HasMany
    {
        return $this->hasMany(MedicationDispense::class, 'location_id', 'id');
    }
}
