<?php

namespace App\Livewire\Admin\Master\Setting;

use App\Helpers\AlertHelper;
use App\Models\Company\Company;
use App\Models\Company\CompanyDetail;
use App\Models\Company\CompanyService;
use App\Models\Country\Country;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class AdminMasterSettingIndex extends Component
{
    use WithFileUploads;

    public $tabs = [
        'universitas',
        // 'satu-sehat',
        // 'seb',
        // 'layanan',
        // 'aplikasi',
    ];

    public $currentTab;

    // General
    public $company_id;

    public $url;

    public $getCountrys = [];

    // Perusahaan
    public $code;

    public $code_name;

    public $name;

    public $code_region;

    public $region;

    public $email_company;

    public $phone;

    public $website;

    public $country;

    public $address;

    public $logo_old;

    public $logo;

    public $logo_potrait_old;

    public $logo_potrait;

    public $background_login_old;

    public $background_login;

    public $is_mark;
    
    public $is_pmb;

    public $tax_id;

    public $industry;

    public $description;

    public $pic_name;

    public $pic_position;

    public $pic_phone;

    public $pic_email;

    public $color_primary;

    public $color_secondary;

    public $quit_password_seb;

    // SEB Configuration
    public $seb_use_encryption;

    public $seb_encryption_key;

    public $seb_show_taskbar;

    public $seb_show_reload_button;

    public $seb_show_time;

    public $seb_show_input_language;

    public $seb_allow_quit;

    public $seb_allow_spell_check;

    public $seb_enable_private_clipboard;

    public $seb_browser_exam_key;

    // App Installers
    public $app_windows;

    public $app_windows_old;

    public $app_mac;

    public $app_mac_old;

    public $app_android;

    public $app_android_old;

    public $app_ios;

    public $app_ios_old;

    // Service
    public $companyServices = [];

    public function mount()
    {
        $this->company_id = auth()->user()->company_id;

        $this->getCountrys = Country::select('code', 'name')->orderBy('name', 'asc')->get()->toArray();
        $this->setTab('universitas');
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
            'is_mark',
            'is_pmb',
            'code_name',
            'region',
            'code_region',
            'background_login_old',
            'background_login',
            'color_primary',
            'color_secondary',
            'quit_password_seb',
            'seb_use_encryption',
            'seb_encryption_key',
            'seb_show_taskbar',
            'seb_show_reload_button',
            'seb_show_time',
            'seb_show_input_language',
            'seb_allow_quit',
            'seb_allow_spell_check',
            'seb_enable_private_clipboard',
            'seb_enable_private_clipboard',
            'seb_browser_exam_key',
            'app_windows',
            'app_windows_old',
            'app_mac',
            'app_mac_old',
            'app_android',
            'app_android_old',
            'app_ios',
            'app_ios_old',
        ]);

        if ($tab === 'universitas' || $tab === 'seb') {
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
                'logo_potrait',
                'pic_phone',
                'pic_email',
                'is_mark',
                'is_pmb',
                'code_name',
                'region',
                'code_region',
                'background_login',
                'color_primary',
                'color_secondary',
                'quit_password_seb',
                'seb_use_encryption',
                'seb_encryption_key',
                'seb_show_taskbar',
                'seb_show_reload_button',
                'seb_show_time',
                'seb_show_input_language',
                'seb_allow_quit',
                'seb_allow_spell_check',
                'seb_enable_private_clipboard',
                'seb_allow_spell_check',
                'seb_enable_private_clipboard',
                'seb_browser_exam_key',
                'app_windows',
                'app_mac',
                'app_android',
                'app_ios',
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
                $this->logo_potrait_old = $company->logo_potrait;
                $this->tax_id = $company->tax_id;
                $this->industry = $company->industry;
                $this->description = $company->description;
                $this->pic_name = $company->pic_name;
                $this->pic_position = $company->pic_position;
                $this->pic_phone = $company->pic_phone;
                $this->pic_email = $company->pic_email;
                $this->is_mark = $company->is_mark;
                $this->is_pmb = $company->is_pmb;
                $this->code_name = $company->code_name;
                $this->region = $company->region;
                $this->code_region = $company->code_region;
                $this->background_login_old = $company->background_login;
                $this->color_primary = $company->color_primary;
                $this->color_secondary = $company->color_secondary;
                $this->quit_password_seb = $company->quit_password_seb;

                // SEB Settings
                $this->seb_use_encryption = $company->seb_use_encryption;
                $this->seb_encryption_key = $company->seb_encryption_key;
                $this->seb_show_taskbar = $company->seb_show_taskbar;
                $this->seb_show_reload_button = $company->seb_show_reload_button;
                $this->seb_show_time = $company->seb_show_time;
                $this->seb_show_input_language = $company->seb_show_input_language;
                $this->seb_allow_quit = $company->seb_allow_quit;
                $this->seb_allow_spell_check = $company->seb_allow_spell_check;
                $this->seb_enable_private_clipboard = $company->seb_enable_private_clipboard;
                $this->seb_browser_exam_key = $company->seb_browser_exam_key;

                // App Installers
                $this->app_windows_old = $company->app_windows;
                $this->app_mac_old = $company->app_mac;
                $this->app_android_old = $company->app_android;
                $this->app_ios_old = $company->app_ios;
            }
        } elseif ($tab === 'layanan') {
            $this->companyServices = CompanyService::select('id', 'start_date', 'company_id', 'service_month_id', 'duration_days', 'is_lifetime')->with('serviceMonth:id,name,description', 'company:id,name,description')->where('company_id', $this->company_id)->get();
        }
        $this->currentTab = $tab;
    }

    public function save()
    {
        if ($this->currentTab === 'universitas' || $this->currentTab === 'seb') {
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
                'logo' => 'nullable|image|max:2048',
                'logo_potrait' => 'nullable|image|max:2048',
                'code_name' => 'required',
                'region' => 'required',
                'code_region' => 'required',
                'background_login' => 'nullable|image|max:2048',
                'color_primary' => 'required',
                'color_secondary' => 'required',
                'is_pmb' => 'required|in:non_pmb,pmb,all',
                'quit_password_seb' => 'nullable|string|max:255',
                'seb_use_encryption' => 'nullable|boolean',
                'seb_encryption_key' => 'nullable|string|max:255',
                'seb_show_taskbar' => 'nullable|boolean',
                'seb_show_reload_button' => 'nullable|boolean',
                'seb_show_time' => 'nullable|boolean',
                'seb_show_input_language' => 'nullable|boolean',
                'seb_allow_quit' => 'nullable|boolean',
                'seb_allow_spell_check' => 'nullable|boolean',
                'seb_enable_private_clipboard' => 'nullable|boolean',
                'seb_enable_private_clipboard' => 'nullable|boolean',
                'seb_browser_exam_key' => 'nullable|string|max:255',
                'app_windows' => 'nullable|file|max:512000', // Max 500MB
                'app_mac' => 'nullable|file|max:512000',
                'app_android' => 'nullable|file|max:512000',
                'app_ios' => 'nullable|file|max:512000',
            ]);

            if ($this->logo) {
                $randomName = Str::random(40).'.'.$this->logo->getClientOriginalExtension();
                $logoPath = $this->logo->storeAs('public/company', $randomName);
                $this->logo = $logoPath; // untuk simpan di database
            } else {
                $this->logo = $this->logo_old; // fallback jika tidak ada upload baru
            }

            if ($this->logo_potrait) {
                $randomName = Str::random(40).'.'.$this->logo_potrait->getClientOriginalExtension();
                $logo_potraitPath = $this->logo_potrait->storeAs('public/company', $randomName);
                $this->logo_potrait = $logo_potraitPath; // untuk simpan di database
            } else {
                $this->logo_potrait = $this->logo_potrait_old; // fallback jika tidak ada upload baru
            }

            if ($this->background_login) {
                $randomName = Str::random(40).'.'.$this->background_login->getClientOriginalExtension();
                $background_loginPath = $this->background_login->storeAs('public/company', $randomName);
                $this->background_login = $background_loginPath; // untuk simpan di database
            } else {
                $this->background_login = $this->background_login_old; // fallback jika tidak ada upload baru
            }

            // App Installer Uploads
            if ($this->app_windows) {
                $randomName = 'windows_'.Str::random(10).'.'.$this->app_windows->getClientOriginalExtension();
                $this->app_windows = $this->app_windows->storeAs('public/company/apps', $randomName);
            } else {
                $this->app_windows = $this->app_windows_old;
            }

            if ($this->app_mac) {
                $randomName = 'mac_'.Str::random(10).'.'.$this->app_mac->getClientOriginalExtension();
                $this->app_mac = $this->app_mac->storeAs('public/company/apps', $randomName);
            } else {
                $this->app_mac = $this->app_mac_old;
            }

            if ($this->app_android) {
                $randomName = 'android_'.Str::random(10).'.'.$this->app_android->getClientOriginalExtension();
                $this->app_android = $this->app_android->storeAs('public/company/apps', $randomName);
            } else {
                $this->app_android = $this->app_android_old;
            }

            if ($this->app_ios) {
                $randomName = 'ios_'.Str::random(10).'.'.$this->app_ios->getClientOriginalExtension();
                $this->app_ios = $this->app_ios->storeAs('public/company/apps', $randomName);
            } else {
                $this->app_ios = $this->app_ios_old;
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
                'logo_potrait' => $this->logo_potrait,
                'tax_id' => $this->tax_id,
                'industry' => $this->industry,
                'description' => $this->description,
                'country' => $this->country,
                'pic_name' => $this->pic_name,
                'pic_position' => $this->pic_position,
                'pic_phone' => $this->pic_phone,
                'pic_email' => $this->pic_email,
                'is_mark' => $this->is_mark,
                'is_pmb' => $this->is_pmb,
                'code_name' => $this->code_name,
                'region' => $this->region,
                'code_region' => $this->code_region,
                'background_login' => $this->background_login,
                'color_primary' => $this->color_primary,
                'color_secondary' => $this->color_secondary,
                'quit_password_seb' => $this->quit_password_seb,
                'seb_use_encryption' => $this->seb_use_encryption,
                'seb_encryption_key' => $this->seb_encryption_key,
                'seb_show_taskbar' => $this->seb_show_taskbar,
                'seb_show_reload_button' => $this->seb_show_reload_button,
                'seb_show_time' => $this->seb_show_time,
                'seb_show_input_language' => $this->seb_show_input_language,
                'seb_allow_quit' => $this->seb_allow_quit,
                'seb_allow_spell_check' => $this->seb_allow_spell_check,
                'seb_enable_private_clipboard' => $this->seb_enable_private_clipboard,
                'seb_enable_private_clipboard' => $this->seb_enable_private_clipboard,
                'seb_browser_exam_key' => $this->seb_browser_exam_key,
                'app_windows' => $this->app_windows,
                'app_mac' => $this->app_mac,
                'app_android' => $this->app_android,
                'app_ios' => $this->app_ios,
            ]);

            CompanyDetail::updateOrCreate([
                'company_id' => $company->id,
            ], [
                'address' => $this->address,
                'country' => $this->country,
            ]);

            // Reset file upload states agar tidak memanggil temporaryUrl() pada string path
            $this->reset(['logo', 'logo_potrait', 'background_login', 'app_windows', 'app_mac', 'app_android', 'app_ios']);

            // Refresh preview paths dari data tersimpan
            $this->logo_old = $company->logo;
            $this->logo_potrait_old = $company->logo_potrait;
            $this->app_windows_old = $company->app_windows;
            $this->app_mac_old = $company->app_mac;
            $this->app_android_old = $company->app_android;
            $this->app_ios_old = $company->app_ios;

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
