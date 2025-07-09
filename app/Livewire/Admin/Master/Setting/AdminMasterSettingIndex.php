<?php

namespace App\Livewire\Admin\Master\Setting;

use App\Helpers\AlertHelper;
use App\Http\Controllers\API\OneHealth\Auth\AuthController;
use App\Models\Company\Company;
use App\Models\Company\CompanyDetail;
use App\Models\Company\CompanyService;
use App\Models\Company\CompanyServiceHistory;
use App\Models\Company\CompanyServiceMonth;
use App\Models\Country\Country;
use App\service\apiservice;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class AdminMasterSettingIndex extends Component
{
    use WithFileUploads;

    public $tabs = [
        'perusahaan',
        // 'satu-sehat',
        'layanan',
    ];

    public $currentTab;

    // General
    public $company_id;

    public $url;

    public $getCountrys = [];

    // Perusahaan
    public $code;

    public $name;

    public $email_company;

    public $phone;

    public $website;

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

    // Service
    public $companyServices = [];

    public function mount()
    {
        $this->company_id = auth()->user()->company_id;

        $this->getCountrys = Country::select('code', 'name')->orderBy('name', 'asc')->get()->toArray();
        $this->setTab('perusahaan');
    }

    public function setTab($tab)
    {
        $this->reset([
            'code',
            'name',
            'email_company',
            'phone',
            'website',
            'country',
            'address',
            'logo_old',
            'logo',
            'tax_id',
            'industry',
            'description',
            'pic_name',
            'pic_position',
            'pic_phone',
            'pic_email',
            'companyServices',
        ]);

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
        } elseif ($tab === 'layanan') {
            $this->companyServices = CompanyService::select('id', 'start_date', 'company_id', 'service_month_id', 'duration_days', 'is_lifetime')->with('serviceMonth:id,name,description', 'company:id,name,description')->where('company_id', $this->company_id)->get();
        }
        $this->currentTab = $tab;
    }

    public function save()
    {
        if ($this->currentTab === 'perusahaan') {
            $this->validate([
                'code' => 'required',
                'name' => 'required',
                'email_company' => 'required',
                'phone' => 'required',
                'country' => 'required',
                'address' => 'required',
                'pic_name' => 'required',
                'pic_position' => 'required',
                'pic_phone' => 'required',
                'pic_email' => 'required',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
                'address' => $this->address,
                'country' => $this->country,
            ]);

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
