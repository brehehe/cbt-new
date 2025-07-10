<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Company{
/**
 * 
 *
 * @property string $id
 * @property string|null $company_id
 * @property string|null $service_id
 * @property string $code
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string|null $website
 * @property string|null $logo
 * @property string|null $tax_id
 * @property string|null $industry
 * @property string|null $description
 * @property string|null $pic_name
 * @property string|null $pic_position
 * @property string|null $pic_email
 * @property string|null $pic_phone
 * @property bool $is_active
 * @property string|null $expires_at
 * @property int $duration_days
 * @property string|null $start_date
 * @property bool $is_central
 * @property bool $is_main
 * @property bool $is_lifetime
 * @property string|null $one_health_access_token save auth access token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property-read \App\Models\Company\OneHealth\OneHealthOrganization|null $OHOrganization
 * @property-read Company|null $company
 * @property-read \App\Models\Company\CompanyDetail|null $companyDetail
 * @property-read \App\Models\Company\OneHealthy|null $oneHealthy
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereDurationDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereIndustry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereIsCentral($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereIsLifetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereIsMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereOneHealthAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company wherePicEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company wherePicName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company wherePicPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company wherePicPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company withoutTrashed()
 */
	class Company extends \Eloquent {}
}

namespace App\Models\Company{
/**
 * 
 *
 * @property string $id
 * @property string|null $company_id
 * @property string|null $one_health_code Kode satusehat
 * @property string|null $facility_code Kode sarana by one health
 * @property string|null $organization_id Nomor organisasi by one health
 * @property string|null $province_code Kode provinsi by one health
 * @property string|null $province
 * @property string|null $city_code Kode kabupaten by one health
 * @property string|null $city
 * @property string|null $district_code Kode kecamatan by one health
 * @property string|null $district
 * @property string|null $sub_district_code Kode kelurahan by one health
 * @property string|null $sub_district
 * @property string|null $postal_code
 * @property string|null $address
 * @property string $country
 * @property string|null $rt Kode RT by one health
 * @property string|null $rw Kode RW by one health
 * @property string $longitude Kode longitude by one health
 * @property string $latitude Kode latitude by one health
 * @property string $altitude Kode altitude by one health
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereAltitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereCityCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereDistrictCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereFacilityCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereOneHealthCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereProvinceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereSubDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereSubDistrictCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail withoutTrashed()
 */
	class CompanyDetail extends \Eloquent {}
}

namespace App\Models\Company{
/**
 * 
 *
 * @property string $id
 * @property string $company_id
 * @property string $service_month_id
 * @property string $start_date
 * @property string|null $expires_at
 * @property int $duration_days
 * @property int $order
 * @property bool $is_lifetime
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Company\CompanyServiceMonth> $companyServiceMonths
 * @property-read int|null $company_service_months_count
 * @property-read \App\Models\Service\Service|null $service
 * @property-read \App\Models\Service\ServiceMonth|null $serviceMonth
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService whereDurationDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService whereIsLifetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService whereServiceMonthId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService withoutTrashed()
 */
	class CompanyService extends \Eloquent {}
}

namespace App\Models\Company{
/**
 * 
 *
 * @property string $id
 * @property string $company_service_id
 * @property string $service_month_id
 * @property string $start_date
 * @property int $duration_days
 * @property string|null $expires_at
 * @property bool $is_lifetime
 * @property string|null $company_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth whereCompanyServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth whereDurationDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth whereIsLifetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth whereServiceMonthId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth withoutTrashed()
 */
	class CompanyServiceMonth extends \Eloquent {}
}

namespace App\Models\Company\OneHealth{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OneHealthOrganization> $OHConditions
 * @property-read int|null $o_h_conditions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OneHealthOrganization> $OHMedicationReqs
 * @property-read int|null $o_h_medication_reqs_count
 * @property-read \App\Models\Company\OneHealth\OneHealthOrganizationAddress|null $OHOrganizationAddress
 * @property-read \App\Models\Company\OneHealth\OneHealthOrganizationIdentifier|null $OHOrganizationIdentifier
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Company\OneHealth\OneHealthOrganizationTelecom> $OHOrganizationTelecoms
 * @property-read int|null $o_h_organization_telecoms_count
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganization onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganization query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganization withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganization withoutTrashed()
 */
	class OneHealthOrganization extends \Eloquent {}
}

namespace App\Models\Company\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Company\OneHealth\OneHealthOrganization|null $OHOrganization
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Company\OneHealth\OneHealthOrganizationAddressExtention> $extentions
 * @property-read int|null $extentions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationAddress onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationAddress withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationAddress withoutTrashed()
 */
	class OneHealthOrganizationAddress extends \Eloquent {}
}

namespace App\Models\Company\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Company\OneHealth\OneHealthOrganizationAddress|null $OHOrganizationAddress
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationAddressExtention newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationAddressExtention newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationAddressExtention onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationAddressExtention query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationAddressExtention withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationAddressExtention withoutTrashed()
 */
	class OneHealthOrganizationAddressExtention extends \Eloquent {}
}

namespace App\Models\Company\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Company\OneHealth\OneHealthOrganization|null $OHOrganization
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationIdentifier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationIdentifier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationIdentifier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationIdentifier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationIdentifier withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationIdentifier withoutTrashed()
 */
	class OneHealthOrganizationIdentifier extends \Eloquent {}
}

namespace App\Models\Company\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Company\OneHealth\OneHealthOrganization|null $OHOrganization
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationTelecom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationTelecom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationTelecom onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationTelecom query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationTelecom withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthOrganizationTelecom withoutTrashed()
 */
	class OneHealthOrganizationTelecom extends \Eloquent {}
}

namespace App\Models\Company{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy withoutTrashed()
 */
	class OneHealthy extends \Eloquent {}
}

namespace App\Models\Country{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string $code
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country withoutTrashed()
 */
	class Country extends \Eloquent {}
}

namespace App\Models\Master\Exam{
/**
 * 
 *
 * @property string $id
 * @property string|null $company_id
 * @property string $name nama tipe ujian
 * @property string $description keteranagan tipe ujian
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType withoutTrashed()
 */
	class ExamType extends \Eloquent {}
}

namespace App\Models\Master\Question{
/**
 * 
 *
 * @property string $id
 * @property string|null $company_id
 * @property string|null $material_category_id
 * @property string $name nama materi ujian
 * @property string $level level materi ujian
 * @property string|null $description penjelasan materi ujian
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\Question\MaterialCategory|null $materialCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Question\Question> $questions
 * @property-read int|null $questions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereMaterialCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material withoutTrashed()
 */
	class Material extends \Eloquent {}
}

namespace App\Models\Master\Question{
/**
 * 
 *
 * @property string $id
 * @property string|null $company_id
 * @property string|null $topic_id
 * @property string|null $material_category_id
 * @property string $name nama kategori materi ujian
 * @property string|null $description deskripsi kategori materi ujian
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MaterialCategory> $childs
 * @property-read int|null $childs_count
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Question\Material> $materials
 * @property-read int|null $materials_count
 * @property-read MaterialCategory|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Question\Question> $questions
 * @property-read int|null $questions_count
 * @property-read \App\Models\Master\Question\Topic|null $topic
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory whereMaterialCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory withoutTrashed()
 */
	class MaterialCategory extends \Eloquent {}
}

namespace App\Models\Master\Question{
/**
 * 
 *
 * @property string $id
 * @property string|null $company_id
 * @property string|null $question_type_id
 * @property string $name Nama modul soal
 * @property int $duration durasi waktu pengerjaan
 * @property string|null $description keterangan modul
 * @property bool $random_question apakah soal dalam modul diacak atau urut
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\Question\QuestionType|null $questionType
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereQuestionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereRandomQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module withoutTrashed()
 */
	class Module extends \Eloquent {}
}

namespace App\Models\Master\Question{
/**
 * 
 *
 * @property string $id
 * @property string|null $company_id
 * @property string|null $topic_id
 * @property string|null $material_category_id
 * @property string|null $material_id
 * @property string|null $question_type_id
 * @property string $question soal
 * @property string|null $images gambar soal
 * @property string|null $description keterangan soal
 * @property float|null $weight_correct score jika soal ini terjawab benar
 * @property float|null $weight_incorrect score jika soal ini terjawab salah
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\Question\Material|null $material
 * @property-read \App\Models\Master\Question\MaterialCategory|null $materialCategory
 * @property-read \App\Models\Master\Question\QuestionType|null $questionType
 * @property-read \App\Models\Master\Question\Topic|null $topic
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereMaterialCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereQuestionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereWeightCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereWeightIncorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question withoutTrashed()
 */
	class Question extends \Eloquent {}
}

namespace App\Models\Master\Question{
/**
 * 
 *
 * @property string $id
 * @property string|null $company_id
 * @property string $name nama tipe soal
 * @property string $description keteranagan tipe soal
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Question\Module> $modules
 * @property-read int|null $modules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Question\Question> $questions
 * @property-read int|null $questions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionType search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionType whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionType whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionType withoutTrashed()
 */
	class QuestionType extends \Eloquent {}
}

namespace App\Models\Master\Question{
/**
 * 
 *
 * @property string $id
 * @property string|null $company_id
 * @property string $name Nama topik ujian
 * @property string|null $description deskripsi dari topik ujian
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Question\MaterialCategory> $materialCategories
 * @property-read int|null $material_categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Question\Question> $questions
 * @property-read int|null $questions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic withoutTrashed()
 */
	class Topic extends \Eloquent {}
}

namespace App\Models\Master\RatingScale{
/**
 * 
 *
 * @property string $id
 * @property string $grade_letter
 * @property int $min_score
 * @property int $max_score
 * @property string|null $description
 * @property string|null $company_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale whereGradeLetter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale whereMaxScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale whereMinScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale withoutTrashed()
 */
	class RatingScale extends \Eloquent {}
}

namespace App\Models\Master\Region{
/**
 * 
 *
 * @property string $id
 * @property string $code
 * @property string|null $parent_code
 * @property string|null $bps_code
 * @property string $name
 * @property int $order
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Region\District> $districts
 * @property-read int|null $districts_count
 * @property-read \App\Models\Master\Region\Province|null $province
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereBpsCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereParentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereUpdatedAt($value)
 */
	class City extends \Eloquent {}
}

namespace App\Models\Master\Region{
/**
 * 
 *
 * @property string $id
 * @property string $code
 * @property string|null $parent_code
 * @property string|null $bps_code
 * @property string $name
 * @property int $order
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Master\Region\City|null $city
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Region\SubDistrict> $subDistricts
 * @property-read int|null $sub_districts_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereBpsCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereParentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District whereUpdatedAt($value)
 */
	class District extends \Eloquent {}
}

namespace App\Models\Master\Region{
/**
 * 
 *
 * @property string $id
 * @property string $code
 * @property string|null $parent_code
 * @property string|null $bps_code
 * @property string $name
 * @property int $order
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Region\City> $cities
 * @property-read int|null $cities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereBpsCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereParentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereUpdatedAt($value)
 */
	class Province extends \Eloquent {}
}

namespace App\Models\Master\Region{
/**
 * 
 *
 * @property string $id
 * @property string $code
 * @property string|null $parent_code
 * @property string|null $bps_code
 * @property string $name
 * @property int $order
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Master\Region\District|null $district
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubDistrict newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubDistrict newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubDistrict query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubDistrict whereBpsCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubDistrict whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubDistrict whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubDistrict whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubDistrict whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubDistrict whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubDistrict whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubDistrict whereParentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubDistrict whereUpdatedAt($value)
 */
	class SubDistrict extends \Eloquent {}
}

namespace App\Models\Master\Timetable{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string|null $module_id
 * @property string|null $supervisors
 * @property string $start_time
 * @property string $end_time
 * @property string|null $description
 * @property string|null $company_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Company\Company|null $module
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereSupervisors($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable withoutTrashed()
 */
	class Timetable extends \Eloquent {}
}

namespace App\Models\Notification{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification withoutTrashed()
 */
	class Notification extends \Eloquent {}
}

namespace App\Models\Role{
/**
 * 
 *
 * @property string $id
 * @property string $role_id
 * @property string|null $company_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Spatie\Role|null $role
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany withoutTrashed()
 */
	class RoleCompany extends \Eloquent {}
}

namespace App\Models\Service{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property bool $is_active
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service withoutTrashed()
 */
	class Service extends \Eloquent {}
}

namespace App\Models\Service{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property int $duration_days
 * @property string $price
 * @property bool $is_trial
 * @property bool $is_lifetime
 * @property bool $is_active
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Service\ServiceMonthDetail> $serviceMonthDetails
 * @property-read int|null $service_month_details_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth whereDurationDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth whereIsLifetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth whereIsTrial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth withoutTrashed()
 */
	class ServiceMonth extends \Eloquent {}
}

namespace App\Models\Service{
/**
 * 
 *
 * @property string $id
 * @property string $service_month_id
 * @property string $service_id
 * @property string $status
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail whereServiceMonthId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail withoutTrashed()
 */
	class ServiceMonthDetail extends \Eloquent {}
}

namespace App\Models\Spatie{
/**
 * 
 *
 * @property string $uuid
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Spatie\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutTrashed()
 */
	class Permission extends \Eloquent {}
}

namespace App\Models\Spatie{
/**
 * 
 *
 * @property string $uuid
 * @property string $name
 * @property string|null $company_id
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Spatie\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withoutTrashed()
 */
	class Role extends \Eloquent {}
}

namespace App\Models\SystemSetting{
/**
 * 
 *
 * @property string $id
 * @property int $tax
 * @property string|null $company_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting withoutTrashed()
 */
	class SystemSetting extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string|null $email
 * @property string|null $nim
 * @property string|null $username
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $profile
 * @property string|null $user_id User Referensi untuk relasi diri sendiri
 * @property string|null $company_id
 * @property int $order
 * @property string|null $alternative_contacts Alternative emails/phones for different contexts
 * @property string $type_user Type of user: employee, or patient
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Company\Company> $companies
 * @property-read int|null $companies_count
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User\UserCompanyRole> $companyRoles
 * @property-read int|null $company_roles_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Spatie\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Spatie\Role> $roles
 * @property-read int|null $roles_count
 * @property-read User|null $user
 * @property-read \App\Models\User\UserDetail|null $userDetail
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User companyChoice($companyId, $is_head = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User companyRole($roleName, $companyId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User companyWithoutRolePasienAndDokter($companyId)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAlternativeContacts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTypeUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App\Models\User{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string $role_id
 * @property string|null $role_company_id
 * @property string|null $company_id
 * @property string|null $medical_record_number Nomor rekam medis untuk pasien (bisa kosong untuk non-pasien)
 * @property bool $is_head Apakah role ini adalah kepala dari perusahaan atau tidak
 * @property bool $is_active Status aktif dari role ini
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Spatie\Role|null $role
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole companyRole($roleName, $companyId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole whereIsHead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole whereMedicalRecordNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole whereRoleCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole withoutTrashed()
 */
	class UserCompanyRole extends \Eloquent {}
}

namespace App\Models\User{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string|null $doctor_id ID dokter, jika pengguna adalah dokter
 * @property string|null $address Alamat lengkap pengguna
 * @property string $country
 * @property string|null $identity_card Foto / path file kartu identitas (KTP, BPJS, dll)
 * @property string|null $blood_group Golongan darah (jika tersedia)
 * @property string|null $administrative_gender Jenis kelamin administratif, mengacu pada terminologi AdministrativeGender
 * @property \Illuminate\Support\Carbon|null $birth_date Tanggal lahir
 * @property string|null $deceased_date Tanggal kematian (jika pasien sudah meninggal)
 * @property string|null $marital_status Status pernikahan sipil, mengacu pada terminologi Marital Status Codes
 * @property string $status Status akun pengguna
 * @property string|null $sip_number Nomor Surat Izin Praktik (hanya untuk dokter)
 * @property string|null $specialization Spesialisasi dokter
 * @property string $doctor_type Tipe dokter (umum atau spesialis)
 * @property string $type Tipe dokter (in house atau out house)
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereAdministrativeGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereBloodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereDeceasedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereDoctorType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereIdentityCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereSipNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereSpecialization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail withoutTrashed()
 */
	class UserDetail extends \Eloquent {}
}

