<?php

namespace App\Models\User;

use App\Models\Company\Company;
use App\Models\Master\Region\City;
use App\Models\Master\Region\District;
use App\Models\Master\Region\Province;
use App\Models\Master\Region\SubDistrict;
use Exception;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserDetail extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    protected $casts = [
        'birth_date' => 'date'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
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

                $model->setProvince();
                $model->setCity();
                $model->setDistrict();
                $model->setSubDistrict();
            } catch (Exception |Throwable $th) {
                DB::rollBack();
                $error = [
                    'message' => $th->getMessage(),
                    'file' => $th->getFile(),
                    'line' => $th->getLine(),
                ];

                Log::error('Ada kesalahan saat boot CompanyDetail sync', $error);
            }


        });
    }

    function setProvince()
    {
        $province = Province::where('code', $this->province_code)->first();

        if (!$province) {
            $this->getProvinceTrait();
        }

        $province = Province::where('code', $this->province_code)->first();
        if ($province) {
            $this->updateQuietly([
                'province' => $province?->name,
            ]);
        }
    }

    function setCity()
    {
        $city = City::where('code', $this->city_code)->where('parent_code', $this->province_code)->first();

        if (!$city) {
            $this->getCityTrait($this->province_code);
        }

        $city = City::where('code', $this->city_code)->where('parent_code', $this->province_code)->first();
        if ($city) {
            $this->updateQuietly([
                'city' => $city?->name,
            ]);
        }
    }

    function setDistrict()
    {
        $district = District::where('code', $this->district_code)->where('parent_code', $this->city_code)->first();

        if (!$district) {
            $this->getDistrictTrait($this->city_code);
        }

        $district = District::where('code', $this->district_code)->where('parent_code', $this->city_code)->first();
        if ($district) {
            $this->updateQuietly([
                'district' => $district?->name,
            ]);
        }
    }

    function setSubDistrict()
    {
        $subDistrict = SubDistrict::where('code', $this->sub_district_code)->where('parent_code', $this->district_code)->first();

        if (!$subDistrict) {
            $this->getSubDistrictTrait($this->district_code);
        }

        $subDistrict = SubDistrict::where('code', $this->sub_district_code)->where('parent_code', $this->district_code)->first();
        if ($subDistrict) {
            $this->updateQuietly([
                'sub_district' => $subDistrict?->name,
            ]);
        }
    }
}
