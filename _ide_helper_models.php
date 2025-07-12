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
 * @property-read Company|null $company
 * @property-read \App\Models\Company\CompanyDetail|null $companyDetail
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company withoutTrashed()
 */
	class Company extends \Eloquent {}
}

namespace App\Models\Company{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyDetail withoutTrashed()
 */
	class CompanyDetail extends \Eloquent {}
}

namespace App\Models\Company{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Company\CompanyServiceMonth> $companyServiceMonths
 * @property-read int|null $company_service_months_count
 * @property-read \App\Models\Service\Service|null $service
 * @property-read \App\Models\Service\ServiceMonth|null $serviceMonth
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService withoutTrashed()
 */
	class CompanyService extends \Eloquent {}
}

namespace App\Models\Company{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyServiceMonth query()
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country withoutTrashed()
 */
	class Country extends \Eloquent {}
}

namespace App\Models\Exam{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\Timetable\Timetable|null $timetable
 * @property-read \App\Models\User\UserTimetable|null $userTimetable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAlert withoutTrashed()
 */
	class ExamAlert extends \Eloquent {}
}

namespace App\Models\Exam{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\Timetable\Timetable|null $timetable
 * @property-read \App\Models\User\UserTimetable|null $userTimetable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamRecording withoutTrashed()
 */
	class ExamRecording extends \Eloquent {}
}

namespace App\Models\Master\Exam{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamType withoutTrashed()
 */
	class ExamType extends \Eloquent {}
}

namespace App\Models\Master\Question{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\Question\Question|null $question
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Answer withoutTrashed()
 */
	class Answer extends \Eloquent {}
}

namespace App\Models\Master\Question{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\Question\MaterialCategory|null $materialCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Question\Question> $questions
 * @property-read int|null $questions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material withoutTrashed()
 */
	class Material extends \Eloquent {}
}

namespace App\Models\Master\Question{
/**
 * 
 *
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialCategory withoutTrashed()
 */
	class MaterialCategory extends \Eloquent {}
}

namespace App\Models\Master\Question{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Question\ModuleQuestion> $moduleQuestions
 * @property-read int|null $module_questions_count
 * @property-read \App\Models\Master\Question\QuestionType|null $questionType
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Module withoutTrashed()
 */
	class Module extends \Eloquent {}
}

namespace App\Models\Master\Question{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\Question\Module|null $module
 * @property-read \App\Models\Master\Question\Question|null $question
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModuleQuestion withoutTrashed()
 */
	class ModuleQuestion extends \Eloquent {}
}

namespace App\Models\Master\Question{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Question\Answer> $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\Question\Material|null $material
 * @property-read \App\Models\Master\Question\MaterialCategory|null $materialCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Question\ModuleQuestion> $moduleQuestions
 * @property-read int|null $module_questions_count
 * @property-read \App\Models\Master\Question\QuestionType|null $questionType
 * @property-read \App\Models\Master\Question\Topic|null $topic
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question withoutTrashed()
 */
	class Question extends \Eloquent {}
}

namespace App\Models\Master\Question{
/**
 * 
 *
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionType withoutTrashed()
 */
	class QuestionType extends \Eloquent {}
}

namespace App\Models\Master\Question{
/**
 * 
 *
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Topic withoutTrashed()
 */
	class Topic extends \Eloquent {}
}

namespace App\Models\Master\RatingScale{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RatingScale withoutTrashed()
 */
	class RatingScale extends \Eloquent {}
}

namespace App\Models\Master\Region{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Region\District> $districts
 * @property-read int|null $districts_count
 * @property-read \App\Models\Master\Region\Province|null $province
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City query()
 */
	class City extends \Eloquent {}
}

namespace App\Models\Master\Region{
/**
 * 
 *
 * @property-read \App\Models\Master\Region\City|null $city
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Region\SubDistrict> $subDistricts
 * @property-read int|null $sub_districts_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|District query()
 */
	class District extends \Eloquent {}
}

namespace App\Models\Master\Region{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Master\Region\City> $cities
 * @property-read int|null $cities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province query()
 */
	class Province extends \Eloquent {}
}

namespace App\Models\Master\Region{
/**
 * 
 *
 * @property-read \App\Models\Master\Region\District|null $district
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubDistrict newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubDistrict newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubDistrict query()
 */
	class SubDistrict extends \Eloquent {}
}

namespace App\Models\Master\Regulation{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regulation withoutTrashed()
 */
	class Regulation extends \Eloquent {}
}

namespace App\Models\Master\Timetable{
/**
 * 
 *
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
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Spatie\Role|null $role
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoleCompany withoutTrashed()
 */
	class RoleCompany extends \Eloquent {}
}

namespace App\Models\Service{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service withoutTrashed()
 */
	class Service extends \Eloquent {}
}

namespace App\Models\Service{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Service\ServiceMonthDetail> $serviceMonthDetails
 * @property-read int|null $service_month_details_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonth withoutTrashed()
 */
	class ServiceMonth extends \Eloquent {}
}

namespace App\Models\Service{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceMonthDetail withoutTrashed()
 */
	class ServiceMonthDetail extends \Eloquent {}
}

namespace App\Models\Spatie{
/**
 * 
 *
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Spatie\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting withoutTrashed()
 */
	class SystemSetting extends \Eloquent {}
}

namespace App\Models\Timetable{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Timetable\TimetableModule|null $timetableModule
 * @property-read \App\Models\Timetable\TimetableQuestion|null $timetableQuestion
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableAnswer withoutTrashed()
 */
	class TimetableAnswer extends \Eloquent {}
}

namespace App\Models\Timetable{
/**
 * 
 *
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableModule withoutTrashed()
 */
	class TimetableModule extends \Eloquent {}
}

namespace App\Models\Timetable{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Timetable\TimetableAnswer> $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Timetable\TimetableModule|null $timetableModule
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimetableQuestion withoutTrashed()
 */
	class TimetableQuestion extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
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
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Spatie\Role|null $role
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole companyRole($roleName, $companyId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserCompanyRole withoutTrashed()
 */
	class UserCompanyRole extends \Eloquent {}
}

namespace App\Models\User{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail withoutTrashed()
 */
	class UserDetail extends \Eloquent {}
}

namespace App\Models\User{
/**
 * 
 *
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModuleQuestion withoutTrashed()
 */
	class UserModuleQuestion extends \Eloquent {}
}

namespace App\Models\User{
/**
 * 
 *
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserTimetable withoutTrashed()
 */
	class UserTimetable extends \Eloquent {}
}

