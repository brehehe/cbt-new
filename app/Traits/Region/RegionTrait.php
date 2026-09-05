<?php

namespace App\Traits\Region;

use App\Models\Master\Region\City;
use App\Models\Master\Region\District;
use App\Models\Master\Region\Province;
use App\Models\Master\Region\SubDistrict;
use Illuminate\Http\Request;

trait RegionTrait
{
    public function getProvinceTrait()
    {
        $details = Province::select('code', 'name')
            ->orderBy('name', 'asc')
            ->get()
            ->toArray();

        if (empty($details) && class_exists('App\Http\Controllers\API\OneHealth\MasterData\RegionController')) {
            $controllerClass = 'App\Http\Controllers\API\OneHealth\MasterData\RegionController';
            (new $controllerClass)->getProvince();

            $details = Province::select('code', 'name')
                ->orderBy('name', 'asc')
                ->get()
                ->toArray();
        }

        return $details;
    }

    public function getCityTrait($provinceCode)
    {
        $details = City::where('parent_code', $provinceCode)
            ->select('code', 'name')
            ->orderBy('name', 'asc')
            ->get()
            ->toArray();

        if (empty($details) && class_exists('App\Http\Controllers\API\OneHealth\MasterData\RegionController')) {
            $request = new Request;
            $request->merge(['province_codes' => $provinceCode]);

            $controllerClass = 'App\Http\Controllers\API\OneHealth\MasterData\RegionController';
            (new $controllerClass)->getCity($request);

            $details = City::where('parent_code', $provinceCode)
                ->select('code', 'name')
                ->orderBy('name', 'asc')
                ->get()
                ->toArray();
        }

        return $details;
    }

    public function getDistrictTrait($cityCode)
    {
        $details = District::where('parent_code', $cityCode)
            ->select('code', 'name')
            ->orderBy('name', 'asc')
            ->get()
            ->toArray();

        if (empty($details) && class_exists('App\Http\Controllers\API\OneHealth\MasterData\RegionController')) {
            $request = new Request;
            $request->merge(['city_codes' => $cityCode]);

            $controllerClass = 'App\Http\Controllers\API\OneHealth\MasterData\RegionController';
            (new $controllerClass)->getDistrict($request);

            $details = District::where('parent_code', $cityCode)
                ->select('code', 'name')
                ->orderBy('name', 'asc')
                ->get()
                ->toArray();
        }

        return $details;
    }

    public function getSubDistrictTrait($districtCode)
    {
        $details = SubDistrict::where('parent_code', $districtCode)
            ->select('code', 'name')
            ->orderBy('name', 'asc')
            ->get()
            ->toArray();

        if (empty($details) && class_exists('App\Http\Controllers\API\OneHealth\MasterData\RegionController')) {
            $request = new Request;
            $request->merge(['district_codes' => $districtCode]);

            $controllerClass = 'App\Http\Controllers\API\OneHealth\MasterData\RegionController';
            (new $controllerClass)->getSubDistrist($request);

            $details = SubDistrict::where('parent_code', $districtCode)
                ->select('code', 'name')
                ->orderBy('name', 'asc')
                ->get()
                ->toArray();
        }

        return $details;
    }
}
