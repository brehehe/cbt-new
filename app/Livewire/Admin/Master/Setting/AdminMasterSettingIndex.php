<?php

namespace App\Livewire\Admin\Master\Setting;

use App\Helpers\AlertHelper;
use App\Http\Controllers\API\OneHealth\Auth\AuthController;
use App\Models\Company\Company;
use App\Models\Company\CompanyDetail;
use App\Models\Company\CompanyService;
use App\Models\Company\CompanyServiceHistory;
use App\Models\Company\CompanyServiceMonth;
use App\Models\Company\OneHealthy;
use App\Models\Country\Country;
use App\service\apiservice;
use App\Traits\Region\RegionTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class AdminMasterSettingIndex extends Component
{
    use RegionTrait, WithFileUploads;

    public $tabs = [
        'perusahaan',
        // 'satu-sehat',
        'layanan',
    ];

    public $currentTab;

    // General
    public $company_id;

    public $getProvinces = [];

    public $getCities = [];

    public $getDistricts = [];

    public $getSubDistricts = [];

    public $url;

    public $getCountrys = [];

    // Perusahaan
    public $code;

    public $name;

    public $email_company;

    public $phone;

    public $website;

    public $province;

    public $city;

    public $district;

    public $sub_district;

    public $postal_code;

    public $country;

    public $address;

    public $logo_old;

    public $logo;

    public $tax_id;

    public $industry;

    public $description;

    public $pic_name;

    public $pic_position;

    public $pic_phone;

    public $pic_email;

    // Satu Sehat
    public $organization_id;

    public $client_id;

    public $client_secret;

    // Service
    public $companyServices = [];

    public function mount()
    {
        $this->company_id = auth()->user()->company_id;

        $access_token = Cache::get('accessToken');

        if (! $access_token) {
            (new AuthController)->accessToken();
            $access_token = Cache::get('accessToken');
        }
        $this->url = Env('APP_URL');

        $this->getProvinces = $this->getProvinceTrait();

        $this->getCountrys = Country::select('code', 'name')->orderBy('name', 'asc')->get()->toArray();
        $this->setTab('perusahaan');
    }

    public function setTab($tab)
    {
        $this->reset(['organization_id', 'client_id', 'client_secret', 'code', 'name', 'email_company', 'phone', 'website', 'province', 'city', 'district', 'sub_district', 'postal_code', 'address', 'logo_old', 'logo', 'tax_id', 'industry', 'description', 'pic_name', 'pic_position', 'pic_phone', 'pic_email', 'companyServices']);

        if ($tab === 'perusahaan') {
            $company = Company::select([
                'id',
                'code',
                'name',
                'email',
                'phone',
                'website',
                'logo',
                'tax_id',
                'industry',
                'description',
                'pic_name',
                'pic_position',
                'pic_phone',
                'pic_email',
            ])->with('companyDetail')->find($this->company_id);

            if ($company) {
                $this->code = $company->code;
                $this->name = $company->name;
                $this->email_company = $company->email;
                $this->phone = $company->phone;
                $this->website = $company->website;
                if ($company->companyDetail->province_code) {
                    $this->province = $company->companyDetail->province_code;
                    $this->updatedProvince(); // Trigger the updatedProvince even
                }
                if ($company->companyDetail->city_code) {
                    $this->city = $company->companyDetail->city_code;
                    $this->updatedCity(); // Trigger the updatedProvince even
                }
                if ($company->companyDetail->district_code) {
                    $this->district = $company->companyDetail->district_code;
                    $this->updatedDistrict(); // Trigger the updatedProvince even
                }

                if ($company->companyDetail->sub_district_code) {
                    $this->sub_district = $company->companyDetail->sub_district_code;
                }

                $this->postal_code = $company->companyDetail->postal_code;
                $this->country = $company->companyDetail->country;
                $this->address = $company->companyDetail->address;
                $this->logo_old = $company->logo;
                $this->tax_id = $company->tax_id;
                $this->industry = $company->industry;
                $this->description = $company->description;
                $this->pic_name = $company->pic_name;
                $this->pic_position = $company->pic_position;
                $this->pic_phone = $company->pic_phone;
                $this->pic_email = $company->pic_email;
            }

            $oneHealth = OneHealthy::where('company_id', $this->company_id)->first();
            if ($oneHealth) {
                $this->organization_id = Crypt::decryptString($oneHealth->organization_id);
                $this->client_id = Crypt::decryptString($oneHealth->client_id);
                $this->client_secret = Crypt::decryptString($oneHealth->client_secret);
            }
        } elseif ($tab === 'layanan') {
            $this->companyServices = CompanyService::select('id', 'start_date', 'company_id', 'service_month_id', 'duration_days', 'is_lifetime')->with('serviceMonth:id,name,description', 'company:id,name,description')->where('company_id', $this->company_id)->get();
        }
        $this->currentTab = $tab;
    }

    public function updatedProvince()
    {
        $this->reset(['getCities', 'city', 'district', 'sub_district']);

        if ($this->province) {
            $this->getCities = $this->getCityTrait($this->province);
        }
    }

    public function updatedCity()
    {
        $this->reset(['getDistricts', 'district', 'sub_district']);

        if ($this->city) {
            $this->getDistricts = $this->getDistrictTrait($this->city);
        }
    }

    public function updatedDistrict()
    {
        $this->reset(['getSubDistricts', 'sub_district']);

        if ($this->district) {
            $this->getSubDistricts = $this->getSubDistrictTrait($this->district);
        }
    }

    public function save()
    {
        if ($this->currentTab === 'perusahaan') {
            $this->validate([
                'code' => 'required',
                'name' => 'required',
                'email_company' => 'required',
                'phone' => 'required',
                'province' => 'required',
                'city' => 'required',
                'district' => 'required',
                'sub_district' => 'required',
                'postal_code' => 'required',
                'country' => 'required',
                'address' => 'required',
                'pic_name' => 'required',
                'pic_position' => 'required',
                'pic_phone' => 'required',
                'pic_email' => 'required',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'organization_id' => 'required',
                'client_id' => 'required',
                'client_secret' => 'required',
            ]);

            if ($this->logo) {
                $randomName = Str::random(40) . '.' . $this->logo->getClientOriginalExtension();
                $logoPath = $this->logo->storeAs('public/company', $randomName);
                $this->logo = $logoPath; // untuk simpan di database
            } else {
                $this->logo = $this->logo_old; // fallback jika tidak ada upload baru
            }

            $company = Company::updateOrCreate([
                'id' => $this->company_id,
            ], [
                'code' => $this->code,
                'name' => $this->name,
                'email' => $this->email_company,
                'phone' => $this->phone,
                'website' => $this->website,
                'logo' => $this->logo,
                'tax_id' => $this->tax_id,
                'industry' => $this->industry,
                'description' => $this->description,
                'country' => $this->country,
                'pic_name' => $this->pic_name,
                'pic_position' => $this->pic_position,
                'pic_phone' => $this->pic_phone,
                'pic_email' => $this->pic_email,
            ]);

            CompanyDetail::updateOrCreate([
                'company_id' => $company->id,
            ], [
                'province_code' => $this->province,
                'city_code' => $this->city,
                'district_code' => $this->district,
                'sub_district_code' => $this->sub_district,
                'postal_code' => $this->postal_code,
                'address' => $this->address,
                'country' => $this->country,
            ]);

            OneHealthy::updateOrCreate([
                'company_id' => $company->id,
            ], [
                'organization_id' => Crypt::encryptString($this->organization_id),
                'client_id' => Crypt::encryptString($this->client_id),
                'client_secret' => Crypt::encryptString($this->client_secret),
            ]);

            app(apiservice::class)->syncCompany($company);

            $this->reset(['logo']);

            $this->logo_old = $company->logo;

            return AlertHelper::success('Berhasil', 'Data berhasil disimpan.');
        }
    }

    public function render()
    {
        return view('livewire.admin.master.setting.admin-master-setting-index')
            ->extends('layout.app')
            ->section('content');
    }
}
