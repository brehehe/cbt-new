<?php

namespace App\Models\User;

use App\Models\Company\Company;
use App\Models\Master\Region\City;
use App\Models\Master\Region\District;
use App\Models\Master\Region\Province;
use App\Models\Master\Region\SubDistrict;
use App\Models\User;
use App\Traits\Region\RegionTrait;
use Exception;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserDetail extends Model
{
    use \App\Traits\LogsSystemActivity, HasFactory, HasUuids, RegionTrait, SoftDeletes;

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'company_id',
        'employee_id',
        'student_id',
        'lecturer_id',
        'address',
        'postal_code',
        'city',
        'province',
        'country',
        'phone',
        'mobile_phone',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'identity_type',
        'identity_number',
        'identity_card_path',
        'blood_group',
        'gender',
        'religion',
        'nationality',
        'birth_date',
        'birth_place',
        'marital_status',
        'student_program',
        'student_faculty',
        'student_department',
        'student_class',
        'student_semester',
        'student_academic_year',
        'student_status',
        'student_gpa',
        'student_advisor_id',
        'student_entry_date',
        'student_graduation_date',
        'lecturer_nidn',
        'lecturer_nip',
        'lecturer_department',
        'lecturer_faculty',
        'lecturer_position',
        'lecturer_functional_position',
        'lecturer_education_level',
        'lecturer_specialization',
        'lecturer_expertise',
        'lecturer_status',
        'lecturer_type',
        'lecturer_start_date',
        'lecturer_retirement_date',
        'certifications',
        'licenses',
        'training_history',
        'awards',
        'exam_preference',
        'special_needs',
        'special_needs_description',
        'exam_history',
        'total_exams_taken',
        'average_score',
        'preferred_language',
        'system_preferences',
        'last_login_at',
        'last_login_ip',
        'notes',
        'verification_status',
        'verified_at',
        'verified_by',
        'status',
        'order',
        'metadata',
        'province_code',
        'city_code',
        'district_code',
        'sub_district_code',
        'district',
        'sub_district',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'student_entry_date' => 'date',
        'student_graduation_date' => 'date',
        'lecturer_start_date' => 'date',
        'lecturer_retirement_date' => 'date',
        'student_gpa' => 'decimal:2',
        'average_score' => 'decimal:2',
        'special_needs' => 'boolean',
        'total_exams_taken' => 'integer',
        'order' => 'integer',
        'last_login_at' => 'datetime',
        'verified_at' => 'datetime',
        'certifications' => 'array',
        'licenses' => 'array',
        'training_history' => 'array',
        'awards' => 'array',
        'exam_history' => 'array',
        'system_preferences' => 'array',
        'metadata' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function studentAdvisor()
    {
        return $this->belongsTo(User::class, 'student_advisor_id');
    }

    // Scopes
    public function scopeStudents($query)
    {
        return $query->whereNotNull('student_id');
    }

    public function scopeLecturers($query)
    {
        return $query->whereNotNull('lecturer_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->user->name ?? '';
    }

    public function getIsStudentAttribute()
    {
        return ! empty($this->student_id);
    }

    public function getIsLecturerAttribute()
    {
        return ! empty($this->lecturer_id);
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    public function getFormattedPhoneAttribute()
    {
        $phone = $this->mobile_phone ?: $this->phone;
        if (! $phone) {
            return null;
        }

        // Format Indonesian phone number
        if (substr($phone, 0, 1) === '0') {
            return '+62'.substr($phone, 1);
        }

        return $phone;
    }

    // Mutators
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9+]/', '', $value);
    }

    public function setMobilePhoneAttribute($value)
    {
        $this->attributes['mobile_phone'] = preg_replace('/[^0-9+]/', '', $value);
    }

    // Helper Methods
    public function updateExamStats($score = null)
    {
        $this->increment('total_exams_taken');

        if ($score !== null) {
            $currentAverage = $this->average_score ?? 0;
            $totalExams = $this->total_exams_taken;

            if ($totalExams > 1) {
                $newAverage = (($currentAverage * ($totalExams - 1)) + $score) / $totalExams;
            } else {
                $newAverage = $score;
            }

            $this->update(['average_score' => round($newAverage, 2)]);
        }
    }

    public function addCertification($certification)
    {
        $certifications = $this->certifications ?? [];
        $certifications[] = array_merge($certification, [
            'added_at' => now()->toISOString(),
        ]);
        $this->update(['certifications' => $certifications]);
    }

    public function addTraining($training)
    {
        $trainings = $this->training_history ?? [];
        $trainings[] = array_merge($training, [
            'added_at' => now()->toISOString(),
        ]);
        $this->update(['training_history' => $trainings]);
    }

    public function addAward($award)
    {
        $awards = $this->awards ?? [];
        $awards[] = array_merge($award, [
            'added_at' => now()->toISOString(),
        ]);
        $this->update(['awards' => $awards]);
    }

    public function updateLastLogin($ip = null)
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip ?: request()->ip(),
        ]);
    }

    public function verify($verifiedBy = null)
    {
        $this->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
            'verified_by' => $verifiedBy ?: auth()->id(),
        ]);
    }

    public function reject()
    {
        $this->update([
            'verification_status' => 'rejected',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);
    }

    // Static methods
    public static function createForStudent($userId, $data = [])
    {
        return static::create(array_merge([
            'user_id' => $userId,
            'student_id' => $data['student_id'] ?? null,
            'student_status' => 'active',
        ], $data));
    }

    public static function createForLecturer($userId, $data = [])
    {
        return static::create(array_merge([
            'user_id' => $userId,
            'lecturer_id' => $data['lecturer_id'] ?? null,
            'lecturer_status' => 'active',
        ], $data));
    }

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
            } catch (Exception|Throwable $th) {
                DB::rollBack();
                $error = [
                    'message' => $th->getMessage(),
                    'file' => $th->getFile(),
                    'line' => $th->getLine(),
                ];

                Log::error('Ada kesalahan saat boot UserDetail sync', $error);
            }
        });
    }

    public function setProvince()
    {
        $province = Province::where('code', $this->province_code)->first();

        if (! $province) {
            $this->getProvinceTrait();
        }

        $province = Province::where('code', $this->province_code)->first();
        if ($province) {
            $this->updateQuietly([
                'province' => $province?->name,
            ]);
        }
    }

    public function setCity()
    {
        $city = City::where('code', $this->city_code)->where('parent_code', $this->province_code)->first();

        if (! $city) {
            $this->getCityTrait($this->province_code);
        }

        $city = City::where('code', $this->city_code)->where('parent_code', $this->province_code)->first();
        if ($city) {
            $this->updateQuietly([
                'city' => $city?->name,
            ]);
        }
    }

    public function setDistrict()
    {
        $district = District::where('code', $this->district_code)->where('parent_code', $this->city_code)->first();

        if (! $district) {
            $this->getDistrictTrait($this->city_code);
        }

        $district = District::where('code', $this->district_code)->where('parent_code', $this->city_code)->first();
        if ($district) {
            $this->updateQuietly([
                'district' => $district?->name,
            ]);
        }
    }

    public function setSubDistrict()
    {
        $subDistrict = SubDistrict::where('code', $this->sub_district_code)->where('parent_code', $this->district_code)->first();

        if (! $subDistrict) {
            $this->getSubDistrictTrait($this->district_code);
        }

        $subDistrict = SubDistrict::where('code', $this->sub_district_code)->where('parent_code', $this->district_code)->first();
        if ($subDistrict) {
            $this->updateQuietly([
                'sub_district' => $subDistrict?->name,
            ]);
        }
    }

    public function setLecturerStartDateAttribute($value)
    {
        $this->attributes['lecturer_start_date'] = $value ?: null;
    }

    public function setBirthDateAttribute($value)
    {
        $this->attributes['birth_date'] = $value ?: null;
    }
}
