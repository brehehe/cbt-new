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
 * @property-read Company|null $company
 * @property-read \App\Models\Company\CompanyDetail|null $companyDetail
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

namespace App\Models\Exam{
/**
 * 
 *
 * @property string $id
 * @property string|null $timetable_id
 * @property string $user_timetable_id
 * @property string $alert_type
 * @property string $description
 * @property array<array-key, mixed>|null $metadata
 * @property string|null $company_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\Timetable\Timetable|null $timetable
 * @property-read \App\Models\User\UserTimetable|null $userTimetable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert whereAlertType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert whereTimetableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert whereUserTimetableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert withoutTrashed()
 */
	class ExamAlert extends \Eloquent {}
}

namespace App\Models\Exam{
/**
 * 
 *
 * @property string $id
 * @property string|null $timetable_id
 * @property string $user_timetable_id
 * @property string|null $video_path
 * @property int $chunk_number
 * @property int|null $file_size
 * @property \Illuminate\Support\Carbon $start_time
 * @property \Illuminate\Support\Carbon|null $end_time
 * @property string $status
 * @property string|null $company_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\Timetable\Timetable|null $timetable
 * @property-read \App\Models\User\UserTimetable|null $userTimetable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording whereChunkNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording whereTimetableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording whereUserTimetableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording whereVideoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording withoutTrashed()
 */
	class ExamRecording extends \Eloquent {}
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
 * @property string|null $question_id
 * @property string|null $alphabet
 * @property string|null $context
 * @property string|null $images
 * @property bool $is_correct
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\Question\Question|null $question
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer whereAlphabet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer whereContext($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer whereIsCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer withoutTrashed()
 */
	class Answer extends \Eloquent {}
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
 * @property string|null $user_id
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Question\ModuleQuestion> $moduleQuestions
 * @property-read int|null $module_questions_count
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module whereUserId($value)
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
 * @property string|null $module_id
 * @property string|null $question_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\Question\Module|null $module
 * @property-read \App\Models\Master\Question\Question|null $question
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion withoutTrashed()
 */
	class ModuleQuestion extends \Eloquent {}
}

namespace App\Models\Master\Question{
/**
 * 
 *
 * @property string $id
 * @property string|null $user_id
 * @property string|null $company_id
 * @property string|null $topic_id
 * @property string|null $material_category_id
 * @property string|null $material_id
 * @property string|null $question_type_id
 * @property string $question soal
 * @property array<array-key, mixed>|null $images gambar soal
 * @property string|null $description keterangan soal
 * @property float|null $weight_correct score jika soal ini terjawab benar
 * @property float|null $weight_incorrect score jika soal ini terjawab salah
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Question\Answer> $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\Question\Material|null $material
 * @property-read \App\Models\Master\Question\MaterialCategory|null $materialCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Question\ModuleQuestion> $moduleQuestions
 * @property-read int|null $module_questions_count
 * @property-read \App\Models\Master\Question\QuestionType|null $questionType
 * @property-read \App\Models\Timetable\TimetableQuestion|null $timetableQuestion
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereUserId($value)
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

namespace App\Models\Master\Regulation{
/**
 * 
 *
 * @property string $id
 * @property string|null $description
 * @property string $type
 * @property string|null $company_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation withoutTrashed()
 */
	class Regulation extends \Eloquent {}
}

namespace App\Models\Master\Timetable{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string|null $module_id
 * @property array<array-key, mixed>|null $supervisors
 * @property \Illuminate\Support\Carbon $start_time
 * @property \Illuminate\Support\Carbon $end_time
 * @property string|null $description
 * @property string|null $code
 * @property string|null $company_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\Question\Module|null $module
 * @property-read \App\Models\Timetable\TimetableModule|null $timetableModule
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User\UserTimetable> $userTimetables
 * @property-read int|null $user_timetables_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereCode($value)
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

namespace App\Models\Timetable{
/**
 * 
 *
 * @property string $id
 * @property string $answer_id
 * @property string $timetable_question_id
 * @property string|null $alphabet
 * @property string|null $context
 * @property string|null $images
 * @property bool $is_correct
 * @property string|null $company_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Timetable\TimetableModule|null $timetableModule
 * @property-read \App\Models\Timetable\TimetableQuestion|null $timetableQuestion
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer whereAlphabet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer whereAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer whereContext($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer whereIsCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer whereTimetableQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer withoutTrashed()
 */
	class TimetableAnswer extends \Eloquent {}
}

namespace App\Models\Timetable{
/**
 * 
 *
 * @property string $id
 * @property string $timetable_id
 * @property string|null $module_id
 * @property string|null $user_id
 * @property string|null $question_type_id
 * @property string $name Nama modul soal
 * @property int $duration durasi waktu pengerjaan
 * @property string|null $description keterangan modul
 * @property bool $random_question apakah soal dalam modul diacak atau urut
 * @property string|null $company_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Timetable\TimetableAnswer> $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Timetable\TimetableQuestion> $questions
 * @property-read int|null $questions_count
 * @property-read \App\Models\Master\Timetable\Timetable|null $timetable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule whereQuestionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule whereRandomQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule whereTimetableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule withoutTrashed()
 */
	class TimetableModule extends \Eloquent {}
}

namespace App\Models\Timetable{
/**
 * 
 *
 * @property string $id
 * @property string $question_id
 * @property string $timetable_module_id
 * @property string|null $user_id
 * @property string|null $topic_id
 * @property string|null $material_category_id
 * @property string|null $material_id
 * @property string|null $question_type_id
 * @property \App\Models\Master\Question\Question|null $question soal
 * @property string|null $images gambar soal
 * @property string|null $description keterangan soal
 * @property float|null $weight_correct score jika soal ini terjawab benar
 * @property float|null $weight_incorrect score jika soal ini terjawab salah
 * @property string|null $company_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Timetable\TimetableAnswer> $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Timetable\TimetableModule|null $timetableModule
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereMaterialCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereQuestionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereTimetableModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereWeightCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion whereWeightIncorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion withoutTrashed()
 */
	class TimetableQuestion extends \Eloquent {}
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

namespace App\Models\User{
/**
 * 
 *
 * @property string $id
 * @property string $user_timetable_id
 * @property string|null $timetable_module_id
 * @property string|null $timetable_question_id
 * @property string|null $timetable_answer_id
 * @property bool $is_mark
 * @property string $status
 * @property string|null $company_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Master\Question\Answer|null $answer
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\Question\ModuleQuestion|null $moduleQuestion
 * @property-read \App\Models\Timetable\TimetableAnswer|null $timetableAnswer
 * @property-read \App\Models\Timetable\TimetableModule|null $timetableModule
 * @property-read \App\Models\Timetable\TimetableQuestion|null $timetableQuestion
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion whereIsMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion whereTimetableAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion whereTimetableModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion whereTimetableQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion whereUserTimetableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion withoutTrashed()
 */
	class UserModuleQuestion extends \Eloquent {}
}

namespace App\Models\User{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string $timetable_id
 * @property string $start_process
 * @property string|null $start_exam
 * @property string|null $end_exam
 * @property int $mark
 * @property string $status
 * @property string|null $company_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\Timetable\Timetable|null $timetable
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User\UserModuleQuestion> $userModuleQuestions
 * @property-read int|null $user_module_questions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable whereEndExam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable whereMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable whereStartExam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable whereStartProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable whereTimetableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable withoutTrashed()
 */
	class UserTimetable extends \Eloquent {}
}

