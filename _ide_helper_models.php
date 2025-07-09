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


namespace App\Models\Account{
/**
 * 
 *
 * @property-read \App\Models\Account\CategoryAccount|null $categoryAccount
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account withoutTrashed()
 */
	class Account extends \Eloquent {}
}

namespace App\Models\Account{
/**
 * 
 *
 * @property-read \App\Models\Account\Account|null $account
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Journal\Journal|null $journal
 * @property-read \App\Models\Journal\JournalItem|null $journalItem
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountTransaction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountTransaction search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountTransaction withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountTransaction withoutTrashed()
 */
	class AccountTransaction extends \Eloquent {}
}

namespace App\Models\Account{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Account\Account> $accounts
 * @property-read int|null $accounts_count
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Account\DetailCategoryAccount|null $detailCategoryAccount
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryAccount onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryAccount search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryAccount withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryAccount withoutTrashed()
 */
	class CategoryAccount extends \Eloquent {}
}

namespace App\Models\Account{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Account\CategoryAccount> $categoryAccounts
 * @property-read int|null $category_accounts_count
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailCategoryAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailCategoryAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailCategoryAccount onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailCategoryAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailCategoryAccount withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailCategoryAccount withoutTrashed()
 */
	class DetailCategoryAccount extends \Eloquent {}
}

namespace App\Models\Branch{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property string $company_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch withoutTrashed()
 */
	class Branch extends \Eloquent {}
}

namespace App\Models\Cash{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cash newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cash newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cash onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cash query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cash withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cash withoutTrashed()
 */
	class Cash extends \Eloquent {}
}

namespace App\Models\Company{
/**
 * 
 *
 * @property string $id
 * @property string|null $company_id
 * @property string $code
 * @property string $name
 * @property string $email_company
 * @property string $phone
 * @property string|null $website
 * @property string $province
 * @property string $city
 * @property string $district
 * @property string $sub_district
 * @property string $postal_code
 * @property string $address
 * @property string $country
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
 * @property bool $is_central
 * @property bool $is_main
 * @property bool $is_lifetime
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property-read \App\Models\Company\OneHealth\OneHealthOrganization|null $OHOrganization
 * @property-read Company|null $company
 * @property-read \App\Models\Company\CompanyDetail|null $companyDetail
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Condition\Condition> $conditions
 * @property-read int|null $conditions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequestDispenseRequest> $medicationReqDispanse
 * @property-read int|null $medication_req_dispanse_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequest> $medicationReqs
 * @property-read int|null $medication_reqs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medication\Medication> $medications
 * @property-read int|null $medications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicineType\MedicineType> $medicineTypes
 * @property-read int|null $medicine_types_count
 * @property-read \App\Models\Company\OneHealthy|null $oneHealthy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Patient\PatientCompany> $patientCompany
 * @property-read int|null $patient_company_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequest> $requestMedicationReqs
 * @property-read int|null $request_medication_reqs_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereEmailCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereIndustry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereIsCentral($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereIsLifetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereIsMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company wherePicEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company wherePicName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company wherePicPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company wherePicPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereSubDistrict($value)
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
 * @property string $id
 * @property string $company_id
 * @property string $service_id
 * @property string $service_month_id
 * @property string $start_date
 * @property int $duration_days
 * @property int $order
 * @property bool $is_lifetime
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService whereIsLifetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyService whereServiceId($value)
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

namespace App\Models\Company\OneHealth{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encounter\OneHealth\OneHealthEncounter> $OHEncounter
 * @property-read int|null $o_h_encounter_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Location\OneHealth\OneHealthLocation> $OHLocations
 * @property-read int|null $o_h_locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medication\OneHealth\OneHealthMedicationIdentifier> $OHMedicationIdentifiers
 * @property-read int|null $o_h_medication_identifiers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestDispenseRequest> $OHMedicationReqDispenseRequest
 * @property-read int|null $o_h_medication_req_dispense_request_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestIdentifier> $OHMedicationReqIdentifiers
 * @property-read int|null $o_h_medication_req_identifiers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OneHealthOrganization> $OHMedicationReqs
 * @property-read int|null $o_h_medication_reqs_count
 * @property-read \App\Models\Company\OneHealth\OneHealthOrganizationAddress|null $OHOrganizationAddress
 * @property-read \App\Models\Company\OneHealth\OneHealthOrganizationIdentifier|null $OHOrganizationIdentifier
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Company\OneHealth\OneHealthOrganizationTelecom> $OHOrganizationTelecoms
 * @property-read int|null $o_h_organization_telecoms_count
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\CodeSystem\Organization\MasterOrganizationType|null $typeCodingCode
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
 * @property-read \App\Models\Master\CodeSystem\Organization\MasterOrganizationAddressType|null $type
 * @property-read \App\Models\Master\CodeSystem\Organization\MasterOrganizationAddressUse|null $use
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
 * @property-read \App\Models\Master\CodeSystem\Organization\MasterOrganizationIndentifierUse|null $use
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
 * @property-read \App\Models\Master\CodeSystem\Organization\MasterOrganizationContactPointSystem|null $system
 * @property-read \App\Models\Master\CodeSystem\Organization\MasterOrganizationContactPointUse|null $use
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
 * @property string $id
 * @property string $company_id
 * @property mixed|null $organization_id
 * @property mixed|null $client_id
 * @property mixed|null $client_secret
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy whereClientSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthy withoutTrashed()
 */
	class OneHealthy extends \Eloquent {}
}

namespace App\Models\Condition{
/**
 * 
 *
 * @property-read \App\Models\Condition\OneHealth\OneHealthCondition|null $OHCondition
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Condition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Condition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Condition onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Condition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Condition withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Condition withoutTrashed()
 */
	class Condition extends \Eloquent {}
}

namespace App\Models\Condition\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Condition\Condition|null $condition
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthCondition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthCondition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthCondition onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthCondition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthCondition withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthCondition withoutTrashed()
 */
	class OneHealthCondition extends \Eloquent {}
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

namespace App\Models\DeadStock{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Product\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeadStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeadStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeadStock onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeadStock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeadStock withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeadStock withoutTrashed()
 */
	class DeadStock extends \Eloquent {}
}

namespace App\Models\Defecta{
/**
 * 
 *
 * @property string $id
 * @property string $product_stock_id
 * @property string $product_id
 * @property string $branch_id
 * @property int $minimum_stock
 * @property int|null $edited_minimum_stock
 * @property string $status
 * @property string|null $company_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Branch\Branch|null $branch
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Product\Product|null $product
 * @property-read \App\Models\Product\ProductStock|null $productStock
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta whereEditedMinimumStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta whereMinimumStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta whereProductStockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Defecta withoutTrashed()
 */
	class Defecta extends \Eloquent {}
}

namespace App\Models\Discount{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount withoutTrashed()
 */
	class Discount extends \Eloquent {}
}

namespace App\Models\Doctor{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor withoutTrashed()
 */
	class Doctor extends \Eloquent {}
}

namespace App\Models\Encounter{
/**
 * 
 *
 * @property-read \App\Models\Encounter\OneHealth\OneHealthEncounter|null $OHEncounter
 * @property-read \App\Models\Master\CodeSystem\Encounter\MasterEncounterActCode|null $classCode
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encounter\EncounterClassHistory> $classHistories
 * @property-read int|null $class_histories_count
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Encounter\EncounterCondition|null $encounterConditon
 * @property-read \App\Models\Encounter\EncounterPractitiont|null $encounterPractitiont
 * @property-read \App\Models\Location\Location|null $location
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequest> $medicationReqs
 * @property-read int|null $medication_reqs_count
 * @property-read \App\Models\Patient\Patient|null $patient
 * @property-read \App\Models\Master\CodeSystem\Encounter\MasterEncounterStatus|null $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encounter\EncounterStatusHistory> $statusHistories
 * @property-read int|null $status_histories_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encounter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encounter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encounter onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encounter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encounter withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Encounter withoutTrashed()
 */
	class Encounter extends \Eloquent {}
}

namespace App\Models\Encounter{
/**
 * 
 *
 * @property-read \App\Models\Master\CodeSystem\Encounter\MasterEncounterActCode|null $classCode
 * @property-read \App\Models\Encounter\Encounter|null $encounter
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterClassHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterClassHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterClassHistory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterClassHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterClassHistory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterClassHistory withoutTrashed()
 */
	class EncounterClassHistory extends \Eloquent {}
}

namespace App\Models\Encounter{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Encounter\Encounter|null $encounter
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterCondition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterCondition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterCondition onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterCondition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterCondition withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterCondition withoutTrashed()
 */
	class EncounterCondition extends \Eloquent {}
}

namespace App\Models\Encounter{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterConditionIcd10 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterConditionIcd10 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterConditionIcd10 onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterConditionIcd10 query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterConditionIcd10 withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterConditionIcd10 withoutTrashed()
 */
	class EncounterConditionIcd10 extends \Eloquent {}
}

namespace App\Models\Encounter{
/**
 * 
 *
 * @property-read \App\Models\Encounter\Encounter|null $encounter
 * @property-read \App\Models\Practitiont\Practitioner|null $practitioner
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterPractitiont newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterPractitiont newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterPractitiont onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterPractitiont query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterPractitiont withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterPractitiont withoutTrashed()
 */
	class EncounterPractitiont extends \Eloquent {}
}

namespace App\Models\Encounter{
/**
 * 
 *
 * @property-read \App\Models\Encounter\Encounter|null $encounter
 * @property-read \App\Models\Master\CodeSystem\Encounter\MasterEncounterStatus|null $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatusHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatusHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatusHistory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatusHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatusHistory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatusHistory withoutTrashed()
 */
	class EncounterStatusHistory extends \Eloquent {}
}

namespace App\Models\Encounter{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterSupportCondition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterSupportCondition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterSupportCondition onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterSupportCondition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterSupportCondition withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterSupportCondition withoutTrashed()
 */
	class EncounterSupportCondition extends \Eloquent {}
}

namespace App\Models\Encounter{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterSupportConditionIcd10 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterSupportConditionIcd10 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterSupportConditionIcd10 onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterSupportConditionIcd10 query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterSupportConditionIcd10 withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterSupportConditionIcd10 withoutTrashed()
 */
	class EncounterSupportConditionIcd10 extends \Eloquent {}
}

namespace App\Models\Encounter\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Encounter\OneHealth\OneHealthEncounterIdentifier|null $OHEncounterIdentifier
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encounter\OneHealth\OneHealthEncounterLocation> $OHEncounterLocations
 * @property-read int|null $o_h_encounter_locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encounter\Onehealth\OneHealthEnconterParticipant> $OHEncounterParticipants
 * @property-read int|null $o_h_encounter_participants_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest> $OHMedicationReqs
 * @property-read int|null $o_h_medication_reqs_count
 * @property-read \App\Models\Company\OneHealth\OneHealthOrganization|null $OHOrganization
 * @property-read \App\Models\Patient\OneHealth\OneHealthPatient|null $OHPatient
 * @property-read \App\Models\Master\CodeSystem\Encounter\MasterEncounterActCode|null $classCode
 * @property-read \App\Models\Encounter\Encounter|null $encounter
 * @property-read \App\Models\Master\CodeSystem\Encounter\MasterEncounterStatus|null $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounter onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounter withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounter withoutTrashed()
 */
	class OneHealthEncounter extends \Eloquent {}
}

namespace App\Models\Encounter\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterCategory withoutTrashed()
 */
	class OneHealthEncounterCategory extends \Eloquent {}
}

namespace App\Models\Encounter\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterClinicalStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterClinicalStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterClinicalStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterClinicalStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterClinicalStatus withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterClinicalStatus withoutTrashed()
 */
	class OneHealthEncounterClinicalStatus extends \Eloquent {}
}

namespace App\Models\Encounter\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterCode onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterCode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterCode withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterCode withoutTrashed()
 */
	class OneHealthEncounterCode extends \Eloquent {}
}

namespace App\Models\Encounter\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Encounter\OneHealth\OneHealthEncounter|null $OHEncounter
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterIdentifier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterIdentifier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterIdentifier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterIdentifier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterIdentifier withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterIdentifier withoutTrashed()
 */
	class OneHealthEncounterIdentifier extends \Eloquent {}
}

namespace App\Models\Encounter\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Encounter\OneHealth\OneHealthEncounter|null $OHEncounter
 * @property-read \App\Models\Location\OneHealth\OneHealthLocation|null $OHLocation
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterLocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterLocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterLocation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterLocation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterLocation withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterLocation withoutTrashed()
 */
	class OneHealthEncounterLocation extends \Eloquent {}
}

namespace App\Models\Encounter\OneHealth{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterNote onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterNote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterNote withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEncounterNote withoutTrashed()
 */
	class OneHealthEncounterNote extends \Eloquent {}
}

namespace App\Models\Encounter\Onehealth{
/**
 * 
 *
 * @property-read \App\Models\Encounter\OneHealth\OneHealthEncounter|null $OHEncounter
 * @property-read \App\Models\Practitiont\OneHealth\OneHealthPractitioner|null $OHPractitioner
 * @property-read \App\Models\Master\CodeSystem\Encounter\MasterEncounterParticipationType|null $typeCodingCode
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEnconterParticipant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEnconterParticipant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEnconterParticipant onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEnconterParticipant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEnconterParticipant withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthEnconterParticipant withoutTrashed()
 */
	class OneHealthEnconterParticipant extends \Eloquent {}
}

namespace App\Models\Finance{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Finance\FinanceItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Finance\FinancePayment|null $paymentFirst
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Finance\FinancePayment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Finance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Finance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Finance onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Finance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Finance search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Finance withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Finance withoutTrashed()
 */
	class Finance extends \Eloquent {}
}

namespace App\Models\Finance{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Finance\Finance|null $finance
 * @property-read \App\Models\Product\Product|null $product
 * @property-read \App\Models\StockOpname\StockOpnameItem|null $stockOpnameItem
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceItem withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceItem withoutTrashed()
 */
	class FinanceItem extends \Eloquent {}
}

namespace App\Models\Finance{
/**
 * 
 *
 * @property-read \App\Models\Account\Account|null $accountDebt
 * @property-read \App\Models\Account\Account|null $accountPayment
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Finance\Finance|null $finance
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancePayment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancePayment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancePayment withoutTrashed()
 */
	class FinancePayment extends \Eloquent {}
}

namespace App\Models\Finance{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceRecipe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceRecipe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceRecipe onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceRecipe query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceRecipe withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinanceRecipe withoutTrashed()
 */
	class FinanceRecipe extends \Eloquent {}
}

namespace App\Models\Icd{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestReasonCode> $OHMedicationReqReasonCode
 * @property-read int|null $o_h_medication_req_reason_code_count
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequest> $medicationReqs
 * @property-read int|null $medication_reqs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Icd10 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Icd10 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Icd10 onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Icd10 query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Icd10 search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Icd10 withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Icd10 withoutTrashed()
 */
	class Icd10 extends \Eloquent {}
}

namespace App\Models\Icd{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Icd9 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Icd9 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Icd9 onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Icd9 query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Icd9 search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Icd9 withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Icd9 withoutTrashed()
 */
	class Icd9 extends \Eloquent {}
}

namespace App\Models\Journal{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Journal\JournalItem> $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal withoutTrashed()
 */
	class Journal extends \Eloquent {}
}

namespace App\Models\Journal{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalItem withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalItem withoutTrashed()
 */
	class JournalItem extends \Eloquent {}
}

namespace App\Models\Location{
/**
 * 
 *
 * @property-read \App\Models\Location\OneHealth\OneHealthLocation|null $OHLocation
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encounter\Encounter> $encounters
 * @property-read int|null $encounters_count
 * @property-read Location|null $location
 * @property-read \App\Models\Master\CodeSystem\Location\MasterLocationMode|null $mode
 * @property-read \App\Models\Master\CodeSystem\Location\MasterLocationType|null $physicalType
 * @property-read \App\Models\Master\CodeSystem\Location\MasterLocationStatus|null $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location withoutTrashed()
 */
	class Location extends \Eloquent {}
}

namespace App\Models\Location\OneHealth{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encounter\OneHealth\OneHealthEncounterLocation> $OHEncounterLocations
 * @property-read int|null $o_h_encounter_locations_count
 * @property-read \App\Models\Location\OneHealth\OneHealthLocationIdentifier|null $OHLIdentifier
 * @property-read \App\Models\Location\OneHealth\OneHealthLocationAddress|null $OHLocationAddress
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Location\OneHealth\OneHealthLocationTelecom> $OHLocationTelecoms
 * @property-read int|null $o_h_location_telecoms_count
 * @property-read \App\Models\Company\OneHealth\OneHealthOrganization|null $OHOrganization
 * @property-read \App\Models\Location\Location|null $location
 * @property-read \App\Models\Master\CodeSystem\Location\MasterLocationMode|null $mode
 * @property-read \App\Models\Master\CodeSystem\Location\MasterLocationType|null $physicalType
 * @property-read \App\Models\Master\CodeSystem\Location\MasterLocationStatus|null $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocation withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocation withoutTrashed()
 */
	class OneHealthLocation extends \Eloquent {}
}

namespace App\Models\Location\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Location\OneHealth\OneHealthLocation|null $OHLocation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Location\OneHealth\OneHealthLocationAddressExtention> $extentions
 * @property-read int|null $extentions_count
 * @property-read \App\Models\Master\CodeSystem\Location\MasterLocationAddressUse|null $use
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationAddress onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationAddress withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationAddress withoutTrashed()
 */
	class OneHealthLocationAddress extends \Eloquent {}
}

namespace App\Models\Location\OneHealth{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationAddressExtention newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationAddressExtention newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationAddressExtention onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationAddressExtention query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationAddressExtention withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationAddressExtention withoutTrashed()
 */
	class OneHealthLocationAddressExtention extends \Eloquent {}
}

namespace App\Models\Location\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Location\OneHealth\OneHealthLocation|null $OHLocation
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationIdentifier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationIdentifier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationIdentifier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationIdentifier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationIdentifier withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationIdentifier withoutTrashed()
 */
	class OneHealthLocationIdentifier extends \Eloquent {}
}

namespace App\Models\Location\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Location\OneHealth\OneHealthLocation|null $OHLocation
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationTelecom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationTelecom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationTelecom onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationTelecom query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationTelecom withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthLocationTelecom withoutTrashed()
 */
	class OneHealthLocationTelecom extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Condition{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionBodySite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionBodySite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionBodySite onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionBodySite query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionBodySite withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionBodySite withoutTrashed()
 */
	class MasterConditionBodySite extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Condition{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionCategory withoutTrashed()
 */
	class MasterConditionCategory extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Condition{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionClinicalStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionClinicalStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionClinicalStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionClinicalStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionClinicalStatus withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionClinicalStatus withoutTrashed()
 */
	class MasterConditionClinicalStatus extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Condition{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionSeverity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionSeverity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionSeverity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionSeverity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionSeverity withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionSeverity withoutTrashed()
 */
	class MasterConditionSeverity extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Condition{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionVerificationStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionVerificationStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionVerificationStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionVerificationStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionVerificationStatus withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConditionVerificationStatus withoutTrashed()
 */
	class MasterConditionVerificationStatus extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Consultation{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationCategoryCondition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationCategoryCondition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationCategoryCondition onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationCategoryCondition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationCategoryCondition withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationCategoryCondition withoutTrashed()
 */
	class MasterConsultationCategoryCondition extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Consultation{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationConditionClinical newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationConditionClinical newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationConditionClinical onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationConditionClinical query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationConditionClinical withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationConditionClinical withoutTrashed()
 */
	class MasterConsultationConditionClinical extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Consultation{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationConditionVerStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationConditionVerStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationConditionVerStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationConditionVerStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationConditionVerStatus withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationConditionVerStatus withoutTrashed()
 */
	class MasterConsultationConditionVerStatus extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Consultation{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationSnomedCT newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationSnomedCT newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationSnomedCT onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationSnomedCT query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationSnomedCT withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationSnomedCT withoutTrashed()
 */
	class MasterConsultationSnomedCT extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Consultation{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationTerminology newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationTerminology newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationTerminology onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationTerminology query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationTerminology withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterConsultationTerminology withoutTrashed()
 */
	class MasterConsultationTerminology extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Encounter{
/**
 * 
 *
 * @property string $id
 * @property string|null $code
 * @property string|null $display
 * @property string|null $definition
 * @property string|null $comments
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActCode onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActCode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActCode whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActCode whereDefinition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActCode whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActCode whereDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActCode whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActCode withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActCode withoutTrashed()
 */
	class ActCode extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Encounter{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActPriority newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActPriority newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActPriority onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActPriority query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActPriority withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActPriority withoutTrashed()
 */
	class ActPriority extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Encounter{
/**
 * 
 *
 * @property string $id
 * @property string $code
 * @property string $display
 * @property string|null $definition
 * @property string|null $comments
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatus whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatus whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatus whereDefinition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatus whereDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatus whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatus withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterStatus withoutTrashed()
 */
	class EncounterStatus extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Encounter{
/**
 * 
 *
 * @property string $id
 * @property string $code
 * @property string $display
 * @property string|null $definition
 * @property string|null $comments
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterType whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterType whereDefinition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterType whereDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterType whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EncounterType withoutTrashed()
 */
	class EncounterType extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Encounter{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encounter\EncounterClassHistory> $classHistories
 * @property-read int|null $class_histories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encounter\Encounter> $encounters
 * @property-read int|null $encounters_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterActCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterActCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterActCode onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterActCode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterActCode withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterActCode withoutTrashed()
 */
	class MasterEncounterActCode extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Encounter{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterActPriority newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterActPriority newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterActPriority onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterActPriority query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterActPriority withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterActPriority withoutTrashed()
 */
	class MasterEncounterActPriority extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Encounter{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterDiagnosisRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterDiagnosisRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterDiagnosisRole onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterDiagnosisRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterDiagnosisRole withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterDiagnosisRole withoutTrashed()
 */
	class MasterEncounterDiagnosisRole extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Encounter{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterIdentifierUse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterIdentifierUse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterIdentifierUse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterIdentifierUse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterIdentifierUse withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterIdentifierUse withoutTrashed()
 */
	class MasterEncounterIdentifierUse extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Encounter{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encounter\Onehealth\OneHealthEnconterParticipant> $OHEncounterParticipants
 * @property-read int|null $o_h_encounter_participants_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterParticipationType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterParticipationType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterParticipationType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterParticipationType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterParticipationType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterParticipationType withoutTrashed()
 */
	class MasterEncounterParticipationType extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Encounter{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterServiceType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterServiceType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterServiceType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterServiceType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterServiceType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterServiceType withoutTrashed()
 */
	class MasterEncounterServiceType extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Encounter{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encounter\OneHealth\OneHealthEncounter> $OHEncounters
 * @property-read int|null $o_h_encounters_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encounter\EncounterStatusHistory> $encounterStatusHistories
 * @property-read int|null $encounter_status_histories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encounter\Encounter> $encounters
 * @property-read int|null $encounters_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterStatus withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterStatus withoutTrashed()
 */
	class MasterEncounterStatus extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Encounter{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterEncounterType withoutTrashed()
 */
	class MasterEncounterType extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Encounter{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParticipationType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParticipationType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParticipationType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParticipationType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParticipationType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParticipationType withoutTrashed()
 */
	class ParticipationType extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Encounter{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceType withoutTrashed()
 */
	class ServiceType extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Location{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Location\OneHealth\OneHealthLocationAddress> $OHLocationAddresses
 * @property-read int|null $o_h_location_addresses_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationAddressUse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationAddressUse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationAddressUse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationAddressUse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationAddressUse withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationAddressUse withoutTrashed()
 */
	class MasterLocationAddressUse extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Location{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationContactPointSystem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationContactPointSystem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationContactPointSystem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationContactPointSystem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationContactPointSystem withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationContactPointSystem withoutTrashed()
 */
	class MasterLocationContactPointSystem extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Location{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationContactPointUse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationContactPointUse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationContactPointUse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationContactPointUse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationContactPointUse withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationContactPointUse withoutTrashed()
 */
	class MasterLocationContactPointUse extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Location{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationIdentifierUse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationIdentifierUse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationIdentifierUse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationIdentifierUse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationIdentifierUse withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationIdentifierUse withoutTrashed()
 */
	class MasterLocationIdentifierUse extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Location{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Location\OneHealth\OneHealthLocation> $OHLocations
 * @property-read int|null $o_h_locations_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationMode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationMode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationMode onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationMode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationMode withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationMode withoutTrashed()
 */
	class MasterLocationMode extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Location{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Location\OneHealth\OneHealthLocation> $OHLocations
 * @property-read int|null $o_h_locations_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationStatus withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationStatus withoutTrashed()
 */
	class MasterLocationStatus extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Location{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Location\OneHealth\OneHealthLocation> $OHLocations
 * @property-read int|null $o_h_locations_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterLocationType withoutTrashed()
 */
	class MasterLocationType extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\MedicationDispanse{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseCategory withoutTrashed()
 */
	class MasterMedicationDispenseCategory extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\MedicationDispanse{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseIdentifierUse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseIdentifierUse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseIdentifierUse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseIdentifierUse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseIdentifierUse withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseIdentifierUse withoutTrashed()
 */
	class MasterMedicationDispenseIdentifierUse extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\MedicationDispanse{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseStatus withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationDispenseStatus withoutTrashed()
 */
	class MasterMedicationDispenseStatus extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\MedicationRequest{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestCategory withoutTrashed()
 */
	class MasterMedicationRequestCategory extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\MedicationRequest{
/**
 * 
 *
 * @property-read \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest|null $OHMedicationReq
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestCourseOfTherapy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestCourseOfTherapy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestCourseOfTherapy onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestCourseOfTherapy query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestCourseOfTherapy withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestCourseOfTherapy withoutTrashed()
 */
	class MasterMedicationRequestCourseOfTherapy extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\MedicationRequest{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestDispenseRequest> $OHMedicationRequestDispenceRequest
 * @property-read int|null $o_h_medication_request_dispence_request_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDispenseExpect newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDispenseExpect newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDispenseExpect onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDispenseExpect query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDispenseExpect withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDispenseExpect withoutTrashed()
 */
	class MasterMedicationRequestDispenseExpect extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\MedicationRequest{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestDispenseRequest> $OHedicationReqDispanseRequest
 * @property-read int|null $o_hedication_req_dispanse_request_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequestDispenseRequest> $medicationReqDispenseRequest
 * @property-read int|null $medication_req_dispense_request_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDispenseInterval newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDispenseInterval newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDispenseInterval onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDispenseInterval query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDispenseInterval withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDispenseInterval withoutTrashed()
 */
	class MasterMedicationRequestDispenseInterval extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\MedicationRequest{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestDosageInstruction> $OHMedicationReqDosageInstructions
 * @property-read int|null $o_h_medication_req_dosage_instructions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequestDosageInstruction> $medicationReqDosageInstructions
 * @property-read int|null $medication_req_dosage_instructions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageDoseRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageDoseRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageDoseRate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageDoseRate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageDoseRate withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageDoseRate withoutTrashed()
 */
	class MasterMedicationRequestDosageDoseRate extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\MedicationRequest{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageDurationUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageDurationUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageDurationUnit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageDurationUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageDurationUnit withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageDurationUnit withoutTrashed()
 */
	class MasterMedicationRequestDosageDurationUnit extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\MedicationRequest{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequestDosageInstruction> $medicationReqDosageInstructions
 * @property-read int|null $medication_req_dosage_instructions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosagePeriodUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosagePeriodUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosagePeriodUnit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosagePeriodUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosagePeriodUnit withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosagePeriodUnit withoutTrashed()
 */
	class MasterMedicationRequestDosagePeriodUnit extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\MedicationRequest{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestDosageInstruction> $OHMedicationReqDosageInstructions
 * @property-read int|null $o_h_medication_req_dosage_instructions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequestDosageInstruction> $medicationReqDosageInstructions
 * @property-read int|null $medication_req_dosage_instructions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageRoute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageRoute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageRoute onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageRoute query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageRoute withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestDosageRoute withoutTrashed()
 */
	class MasterMedicationRequestDosageRoute extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\MedicationRequest{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestIdentifierUse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestIdentifierUse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestIdentifierUse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestIdentifierUse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestIdentifierUse withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestIdentifierUse withoutTrashed()
 */
	class MasterMedicationRequestIdentifierUse extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\MedicationRequest{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest> $OHMedicationReqs
 * @property-read int|null $o_h_medication_reqs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequest> $medicationReqs
 * @property-read int|null $medication_reqs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestIntent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestIntent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestIntent onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestIntent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestIntent withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestIntent withoutTrashed()
 */
	class MasterMedicationRequestIntent extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\MedicationRequest{
/**
 * 
 *
 * @property-read mixed $code_display
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequestDosageInstruction> $medicationReqDosageInstructions
 * @property-read int|null $medication_req_dosage_instructions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestOrderableDrugForm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestOrderableDrugForm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestOrderableDrugForm onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestOrderableDrugForm query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestOrderableDrugForm withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestOrderableDrugForm withoutTrashed()
 */
	class MasterMedicationRequestOrderableDrugForm extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\MedicationRequest{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest> $OHMedicationReq
 * @property-read int|null $o_h_medication_req_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequest> $medicationReq
 * @property-read int|null $medication_req_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestPriority newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestPriority newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestPriority onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestPriority query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestPriority withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestPriority withoutTrashed()
 */
	class MasterMedicationRequestPriority extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\MedicationRequest{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest> $OHMedicationReq
 * @property-read int|null $o_h_medication_req_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequest> $medicationReq
 * @property-read int|null $medication_req_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestStatus withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestStatus withoutTrashed()
 */
	class MasterMedicationRequestStatus extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\MedicationRequest{
/**
 * 
 *
 * @property-read mixed $code_display
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequestDosageInstruction> $medicationReqDosageInstructions
 * @property-read int|null $medication_req_dosage_instructions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestValueQuantity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestValueQuantity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestValueQuantity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestValueQuantity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestValueQuantity withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationRequestValueQuantity withoutTrashed()
 */
	class MasterMedicationRequestValueQuantity extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Medication{
/**
 * 
 *
 * @property-read mixed $code_display
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medication\Medication> $medications
 * @property-read int|null $medications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationForm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationForm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationForm onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationForm query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationForm withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationForm withoutTrashed()
 */
	class MasterMedicationForm extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Medication{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationIdentifierUse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationIdentifierUse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationIdentifierUse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationIdentifierUse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationIdentifierUse withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationIdentifierUse withoutTrashed()
 */
	class MasterMedicationIdentifierUse extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Medication{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationOrderableDrugForm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationOrderableDrugForm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationOrderableDrugForm onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationOrderableDrugForm query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationOrderableDrugForm withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationOrderableDrugForm withoutTrashed()
 */
	class MasterMedicationOrderableDrugForm extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Medication{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medication\OneHealth\OneHealthMedication> $OHMedication
 * @property-read int|null $o_h_medication_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medication\Medication> $medication
 * @property-read int|null $medication_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationStatus withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationStatus withoutTrashed()
 */
	class MasterMedicationStatus extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Medication{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medication\Medication> $medications
 * @property-read int|null $medications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationType withoutTrashed()
 */
	class MasterMedicationType extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Medication{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationValueQuantity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationValueQuantity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationValueQuantity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationValueQuantity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationValueQuantity withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterMedicationValueQuantity withoutTrashed()
 */
	class MasterMedicationValueQuantity extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Organization{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationAddressType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationAddressType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationAddressType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationAddressType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationAddressType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationAddressType withoutTrashed()
 */
	class MasterOrganizationAddressType extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Organization{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationAddressUse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationAddressUse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationAddressUse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationAddressUse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationAddressUse withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationAddressUse withoutTrashed()
 */
	class MasterOrganizationAddressUse extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Organization{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationContactPointSystem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationContactPointSystem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationContactPointSystem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationContactPointSystem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationContactPointSystem withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationContactPointSystem withoutTrashed()
 */
	class MasterOrganizationContactPointSystem extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Organization{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MasterOrganizationContactPointUse> $use
 * @property-read int|null $use_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationContactPointUse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationContactPointUse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationContactPointUse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationContactPointUse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationContactPointUse withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationContactPointUse withoutTrashed()
 */
	class MasterOrganizationContactPointUse extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Organization{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Company\OneHealth\OneHealthOrganizationIdentifier> $OHOrganizationIdentifier
 * @property-read int|null $o_h_organization_identifier_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationIndentifierUse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationIndentifierUse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationIndentifierUse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationIndentifierUse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationIndentifierUse withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationIndentifierUse withoutTrashed()
 */
	class MasterOrganizationIndentifierUse extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Organization{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Company\OneHealth\OneHealthOrganization> $OHOrganization
 * @property-read int|null $o_h_organization_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterOrganizationType withoutTrashed()
 */
	class MasterOrganizationType extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Patient{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientAddressUse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientAddressUse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientAddressUse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientAddressUse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientAddressUse withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientAddressUse withoutTrashed()
 */
	class MasterPatientAddressUse extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Patient{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Patient\OneHealth\OneHealthPatient> $OHPatient
 * @property-read int|null $o_h_patient_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Patient\Patient> $patient
 * @property-read int|null $patient_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientAdministrativeGender newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientAdministrativeGender newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientAdministrativeGender onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientAdministrativeGender query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientAdministrativeGender withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientAdministrativeGender withoutTrashed()
 */
	class MasterPatientAdministrativeGender extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Patient{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactPointSystem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactPointSystem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactPointSystem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactPointSystem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactPointSystem withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactPointSystem withoutTrashed()
 */
	class MasterPatientContactPointSystem extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Patient{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactPointUse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactPointUse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactPointUse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactPointUse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactPointUse withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactPointUse withoutTrashed()
 */
	class MasterPatientContactPointUse extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Patient{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Patient\PatientContactRelationship> $patientContactRelationships
 * @property-read int|null $patient_contact_relationships_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactRelationship newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactRelationship newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactRelationship onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactRelationship query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactRelationship withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientContactRelationship withoutTrashed()
 */
	class MasterPatientContactRelationship extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Patient{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientIdentifierUse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientIdentifierUse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientIdentifierUse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientIdentifierUse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientIdentifierUse withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientIdentifierUse withoutTrashed()
 */
	class MasterPatientIdentifierUse extends \Eloquent {}
}

namespace App\Models\Master\CodeSystem\Patient{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Patient\OneHealth\OneHealthPatient> $OHPatient
 * @property-read int|null $o_h_patient_count
 * @property-read mixed $display_ind
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Patient\Patient> $patient
 * @property-read int|null $patient_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientMaritalStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientMaritalStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientMaritalStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientMaritalStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientMaritalStatus withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MasterPatientMaritalStatus withoutTrashed()
 */
	class MasterPatientMaritalStatus extends \Eloquent {}
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

namespace App\Models\MedicationRequest{
/**
 * 
 *
 * @property-read \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest|null $OHMedicationReq
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequestDosageInstruction> $dosageInstructions
 * @property-read int|null $dosage_instructions_count
 * @property-read \App\Models\Encounter\Encounter|null $encounter
 * @property-read \App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestIntent|null $intent
 * @property-read \App\Models\Medication\Medication|null $medication
 * @property-read \App\Models\MedicationRequest\MedicationRequestDispenseRequest|null $medicationReqDispense
 * @property-read \App\Models\Patient\Patient|null $patient
 * @property-read \App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestPriority|null $priority
 * @property-read \App\Models\Icd\Icd10|null $reasonCode
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $requestable
 * @property-read \App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestStatus|null $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequest withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequest withoutTrashed()
 */
	class MedicationRequest extends \Eloquent {}
}

namespace App\Models\MedicationRequest{
/**
 * 
 *
 * @property-read \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestDispenseRequest|null $OHMedicationReqDispanseRequest
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\MedicationRequest\MedicationRequest|null $medicationReq
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequestDispenseRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequestDispenseRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequestDispenseRequest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequestDispenseRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequestDispenseRequest withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequestDispenseRequest withoutTrashed()
 */
	class MedicationRequestDispenseRequest extends \Eloquent {}
}

namespace App\Models\MedicationRequest{
/**
 * 
 *
 * @property-read \App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestValueQuantity|null $dosageRateQuantityValue
 * @property-read \App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestOrderableDrugForm|null $doseRateQuantityOrderable
 * @property-read \App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDosageDoseRate|null $doseTypeCodingCode
 * @property-read \App\Models\MedicationRequest\MedicationRequest|null $medicationReq
 * @property-read \App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDosageRoute|null $routeTimingCode
 * @property-read \App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDosagePeriodUnit|null $timingRepeatPeriodUnit
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequestDosageInstruction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequestDosageInstruction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequestDosageInstruction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequestDosageInstruction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequestDosageInstruction withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationRequestDosageInstruction withoutTrashed()
 */
	class MedicationRequestDosageInstruction extends \Eloquent {}
}

namespace App\Models\MedicationRequest\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Encounter\OneHealth\OneHealthEncounter|null $OHEncounter
 * @property-read \App\Models\Medication\OneHealth\OneHealthMedication|null $OHMedication
 * @property-read \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestCategory|null $OHMedicationReqCategory
 * @property-read \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestCourseTherapy|null $OHMedicationReqCourseTherapy
 * @property-read \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestDispenseRequest|null $OHMedicationReqDispenseRequest
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestDosageInstruction> $OHMedicationReqDosageInstructions
 * @property-read int|null $o_h_medication_req_dosage_instructions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestIdentifier> $OHMedicationReqIdentifiers
 * @property-read int|null $o_h_medication_req_identifiers_count
 * @property-read \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestReasonCode|null $OHMedicationReqReasonCode
 * @property-read \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestRequester|null $OHMedicationReqRequester
 * @property-read \App\Models\Company\OneHealth\OneHealthOrganization|null $OHOrganization
 * @property-read \App\Models\Patient\OneHealth\OneHealthPatient|null $OHPatient
 * @property-read \App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestIntent|null $intent
 * @property-read \App\Models\MedicationRequest\MedicationRequest|null $medicationReq
 * @property-read \App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestPriority|null $priority
 * @property-read \App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestStatus|null $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequest withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequest withoutTrashed()
 */
	class OneHealthMedicationRequest extends \Eloquent {}
}

namespace App\Models\MedicationRequest\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest|null $OHMedicationReq
 * @property-read \App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestCategory|null $category
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestCategory withoutTrashed()
 */
	class OneHealthMedicationRequestCategory extends \Eloquent {}
}

namespace App\Models\MedicationRequest\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest|null $OHMedicationReq
 * @property-read \App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestCourseOfTherapy|null $codingCode
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestCourseTherapy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestCourseTherapy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestCourseTherapy onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestCourseTherapy query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestCourseTherapy withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestCourseTherapy withoutTrashed()
 */
	class OneHealthMedicationRequestCourseTherapy extends \Eloquent {}
}

namespace App\Models\MedicationRequest\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest|null $OHMedictionReq
 * @property-read \App\Models\Company\OneHealth\OneHealthOrganization|null $OHOrganization
 * @property-read \App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDispenseInterval|null $dispenseIntervalCode
 * @property-read \App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDispenseExpect|null $expectCode
 * @property-read \App\Models\MedicationRequest\MedicationRequestDispenseRequest|null $medicationReqDispenseRequest
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestDispenseRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestDispenseRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestDispenseRequest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestDispenseRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestDispenseRequest withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestDispenseRequest withoutTrashed()
 */
	class OneHealthMedicationRequestDispenseRequest extends \Eloquent {}
}

namespace App\Models\MedicationRequest\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest|null $OHMedicationReq
 * @property-read \App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDosageDoseRate|null $doseRateType
 * @property-read \App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDosageRoute|null $routeCodingCode
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestDosageInstruction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestDosageInstruction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestDosageInstruction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestDosageInstruction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestDosageInstruction withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestDosageInstruction withoutTrashed()
 */
	class OneHealthMedicationRequestDosageInstruction extends \Eloquent {}
}

namespace App\Models\MedicationRequest\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest|null $OHMedicationReq
 * @property-read \App\Models\Company\OneHealth\OneHealthOrganization|null $OHOrganization
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestIdentifier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestIdentifier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestIdentifier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestIdentifier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestIdentifier withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestIdentifier withoutTrashed()
 */
	class OneHealthMedicationRequestIdentifier extends \Eloquent {}
}

namespace App\Models\MedicationRequest\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest|null $OHMedicationReq
 * @property-read \App\Models\Icd\Icd10|null $codingCode
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestReasonCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestReasonCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestReasonCode onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestReasonCode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestReasonCode withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestReasonCode withoutTrashed()
 */
	class OneHealthMedicationRequestReasonCode extends \Eloquent {}
}

namespace App\Models\MedicationRequest\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest|null $OHMedicationReq
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestRequester newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestRequester newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestRequester onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestRequester query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestRequester withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationRequestRequester withoutTrashed()
 */
	class OneHealthMedicationRequestRequester extends \Eloquent {}
}

namespace App\Models\Medication{
/**
 * 
 *
 * @property-read \App\Models\Medication\OneHealth\OneHealthMedication|null $OHMedication
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Master\CodeSystem\Medication\MasterMedicationForm|null $formCodingCode
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medication\MedicationIngredient> $medicationIngredients
 * @property-read int|null $medication_ingredients_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequest> $medicationReqs
 * @property-read int|null $medication_reqs_count
 * @property-read \App\Models\Master\CodeSystem\Medication\MasterMedicationType|null $medicationType
 * @property-read \App\Models\Master\CodeSystem\Medication\MasterMedicationStatus|null $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication withoutTrashed()
 */
	class Medication extends \Eloquent {}
}

namespace App\Models\Medication{
/**
 * 
 *
 * @property-read \App\Models\Medication\OneHealth\OneHealthMedicationIngredient|null $OHMedictionIngredient
 * @property-read \App\Models\Medication\Medication|null $medication
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationIngredient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationIngredient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationIngredient onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationIngredient query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationIngredient withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicationIngredient withoutTrashed()
 */
	class MedicationIngredient extends \Eloquent {}
}

namespace App\Models\Medication\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Medication\OneHealth\OneHealthMedicationExtension|null $OHExtension
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medication\OneHealth\OneHealthMedicationIngredient> $OHIngredients
 * @property-read int|null $o_h_ingredients_count
 * @property-read \App\Models\Medication\OneHealth\OneHealthMedicationCode|null $OHMedicationCode
 * @property-read \App\Models\Medication\OneHealth\OneHealthMedicationForm|null $OHMedicationForm
 * @property-read \App\Models\Medication\OneHealth\OneHealthMedicationIdentifier|null $OHMedicationIdentifier
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest> $OHMedicationReqs
 * @property-read int|null $o_h_medication_reqs_count
 * @property-read \App\Models\Medication\Medication|null $medication
 * @property-read \App\Models\Master\CodeSystem\Medication\MasterMedicationStatus|null $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedication onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedication query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedication withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedication withoutTrashed()
 */
	class OneHealthMedication extends \Eloquent {}
}

namespace App\Models\Medication\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Medication\OneHealth\OneHealthMedication|null $OHMedication
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationCode onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationCode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationCode withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationCode withoutTrashed()
 */
	class OneHealthMedicationCode extends \Eloquent {}
}

namespace App\Models\Medication\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Medication\OneHealth\OneHealthMedication|null $OHMedication
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationExtension newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationExtension newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationExtension onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationExtension query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationExtension withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationExtension withoutTrashed()
 */
	class OneHealthMedicationExtension extends \Eloquent {}
}

namespace App\Models\Medication\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Medication\OneHealth\OneHealthMedication|null $OHMedication
 * @property-read \App\Models\Master\CodeSystem\Medication\MasterMedicationForm|null $masterForm
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationForm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationForm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationForm onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationForm query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationForm withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationForm withoutTrashed()
 */
	class OneHealthMedicationForm extends \Eloquent {}
}

namespace App\Models\Medication\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Medication\OneHealth\OneHealthMedication|null $OHMedication
 * @property-read \App\Models\Company\OneHealth\OneHealthOrganization|null $OHOrganization
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationIdentifier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationIdentifier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationIdentifier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationIdentifier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationIdentifier withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationIdentifier withoutTrashed()
 */
	class OneHealthMedicationIdentifier extends \Eloquent {}
}

namespace App\Models\Medication\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Medication\OneHealth\OneHealthMedication|null $OHMedication
 * @property-read \App\Models\Medication\MedicationIngredient|null $medictionIngredient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationIngredient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationIngredient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationIngredient onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationIngredient query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationIngredient withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthMedicationIngredient withoutTrashed()
 */
	class OneHealthMedicationIngredient extends \Eloquent {}
}

namespace App\Models\MedicineType{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineType withoutTrashed()
 */
	class MedicineType extends \Eloquent {}
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

namespace App\Models\Patient\OneHealth{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encounter\OneHealth\OneHealthEncounter> $OHEncounter
 * @property-read int|null $o_h_encounter_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest> $OHMedicationReqs
 * @property-read int|null $o_h_medication_reqs_count
 * @property-read \App\Models\Patient\OneHealth\OneHealthPatientAddress|null $OHPatientAddress
 * @property-read \App\Models\Patient\OneHealth\OneHealthPatientContactRelationship|null $OHPatientContactRelationship
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Patient\OneHealth\OneHealthPatientIdentifier> $OHPatientIdentifiers
 * @property-read int|null $o_h_patient_identifiers_count
 * @property-read \App\Models\Master\CodeSystem\Patient\MasterPatientAdministrativeGender|null $gender
 * @property-read \App\Models\Master\CodeSystem\Patient\MasterPatientMaritalStatus|null $maritalStatus
 * @property-read \App\Models\Patient\Patient|null $patient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatient onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatient query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatient withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatient withoutTrashed()
 */
	class OneHealthPatient extends \Eloquent {}
}

namespace App\Models\Patient\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Patient\OneHealth\OneHealthPatient|null $OHPatient
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Patient\OneHealth\OneHealthPatientAddressExtension> $extensions
 * @property-read int|null $extensions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientAddress onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientAddress withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientAddress withoutTrashed()
 */
	class OneHealthPatientAddress extends \Eloquent {}
}

namespace App\Models\Patient\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Patient\OneHealth\OneHealthPatientAddress|null $OHPatientAddress
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientAddressExtension newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientAddressExtension newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientAddressExtension onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientAddressExtension query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientAddressExtension withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientAddressExtension withoutTrashed()
 */
	class OneHealthPatientAddressExtension extends \Eloquent {}
}

namespace App\Models\Patient\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Patient\OneHealth\OneHealthPatient|null $OHPatient
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Patient\OneHealth\OneHealthPatientContactTelecom> $contactTelecoms
 * @property-read int|null $contact_telecoms_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientContactRelationship newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientContactRelationship newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientContactRelationship onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientContactRelationship query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientContactRelationship withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientContactRelationship withoutTrashed()
 */
	class OneHealthPatientContactRelationship extends \Eloquent {}
}

namespace App\Models\Patient\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Patient\OneHealth\OneHealthPatientContactRelationship|null $OHPatientContactRelationship
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientContactTelecom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientContactTelecom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientContactTelecom onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientContactTelecom query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientContactTelecom withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientContactTelecom withoutTrashed()
 */
	class OneHealthPatientContactTelecom extends \Eloquent {}
}

namespace App\Models\Patient\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Patient\OneHealth\OneHealthPatient|null $OHPatient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientIdentifier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientIdentifier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientIdentifier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientIdentifier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientIdentifier withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPatientIdentifier withoutTrashed()
 */
	class OneHealthPatientIdentifier extends \Eloquent {}
}

namespace App\Models\Patient{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property mixed|null $identity_card
 * @property string|null $blood_group
 * @property string $administrative_gender Berisi data jenis kelamin pasien dengan tipe data code, yang nilainya mengacu pada salah satu data di terminologi dengan nama AdministrativeGender
 * @property \Illuminate\Support\Carbon $birth_date Berisi data tanggal lahir pasien.
 * @property \Illuminate\Support\Carbon|null $deceased_date Berisi data yang menunjukkan apakah individu tersebut meninggal atau tidak.
 * @property string $marital_status Berisi data status perkawinan (sipil) terakhir pasien dengan tipe data Coding, yang nilainya mengacu pada data terminologi Marital Status Codes.
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Patient\OneHealth\OneHealthPatient|null $OHPatient
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encounter\Encounter> $encounters
 * @property-read int|null $encounters_count
 * @property-read \App\Models\Master\CodeSystem\Patient\MasterPatientAdministrativeGender|null $gender
 * @property-read \App\Models\Master\CodeSystem\Patient\MasterPatientMaritalStatus|null $maritalStatus
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequest> $medicationReqs
 * @property-read int|null $medication_reqs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Patient\PatientCompany> $patientCompany
 * @property-read int|null $patient_company_count
 * @property-read \App\Models\Patient\PatientContactRelationship|null $patientContactRelationship
 * @property-read \App\Models\Patient\PatientDetail|null $patientDetail
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequest> $requestMedicationReqs
 * @property-read int|null $request_medication_reqs_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereAdministrativeGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereBloodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereDeceasedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereIdentityCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient withoutTrashed()
 */
	class Patient extends \Eloquent {}
}

namespace App\Models\Patient{
/**
 * 
 *
 * @property string $id
 * @property string $patient_id
 * @property string $use Berisi data penggunaan alamat pasien dengan tipe data code, yang nilainya mengacu pada data terminologi AddressUse
 * @property string $line Berisi satu atau lebih data nama, blok, no jalan atau no rumah dengan tipe data
 * @property string $city Berisi satu data kota
 * @property string $postal_code Berisi data kode pos
 * @property string $country Berisi data kode negara berdasarkan ISO 3316 2-letter (contoh: ID)
 * @property string $province_code Berisi satu data kode provinsi
 * @property string $city_code Berisi satu data kode kota
 * @property string $district_code Berisi satu data kode kecamatan
 * @property string $sub_district_code Berisi satu data kode kelurahan
 * @property string $rt Berisi satu data nomor rt
 * @property string $rw Berisi satu data nomor rw
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property-read \App\Models\Patient\Patient|null $patient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress whereCityCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress whereDistrictCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress whereLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress whereProvinceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress whereRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress whereRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress whereSubDistrictCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress whereUse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientAddress withoutTrashed()
 */
	class PatientAddress extends \Eloquent {}
}

namespace App\Models\Patient{
/**
 * 
 *
 * @property string $id
 * @property string $patient_id
 * @property string $company_id
 * @property string $medical_number_record
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Patient\Patient|null $patient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientCompany newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientCompany newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientCompany onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientCompany query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientCompany whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientCompany whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientCompany whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientCompany whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientCompany whereMedicalNumberRecord($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientCompany whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientCompany wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientCompany whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientCompany withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientCompany withoutTrashed()
 */
	class PatientCompany extends \Eloquent {}
}

namespace App\Models\Patient{
/**
 * 
 *
 * @property string $id
 * @property string $contactable_type
 * @property string $contactable_id
 * @property string $use Berisi data penggunaan kontak dengan tipe data code, yang nilainya mengacu pada data terminologi ContactPointUse
 * @property string $system Berisi data jenis kontak dengan tipe data code, yang nilainya mengacu pada data terminologi ContactPointSystem
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property-read \App\Models\Patient\Patient|null $patient
 * @property-read \App\Models\Master\CodeSystem\Patient\MasterPatientContactRelationship|null $relationshipCodingCode
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContact onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContact query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContact whereContactableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContact whereContactableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContact whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContact whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContact whereSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContact whereUse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContact whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContact withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContact withoutTrashed()
 */
	class PatientContact extends \Eloquent {}
}

namespace App\Models\Patient{
/**
 * 
 *
 * @property-read \App\Models\Patient\Patient|null $patient
 * @property-read \App\Models\Master\CodeSystem\Patient\MasterPatientContactRelationship|null $relationshipCodingCode
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContactRelationship newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContactRelationship newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContactRelationship onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContactRelationship query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContactRelationship withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientContactRelationship withoutTrashed()
 */
	class PatientContactRelationship extends \Eloquent {}
}

namespace App\Models\Patient{
/**
 * 
 *
 * @property-read \App\Models\Patient\Patient|null $patient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientDetail withoutTrashed()
 */
	class PatientDetail extends \Eloquent {}
}

namespace App\Models\Patient{
/**
 * 
 *
 * @property string $id
 * @property string $patient_id
 * @property string $use Berisi data dengan tipe data code, yang nilainya mengacu pada data terminologi IdentifierUse
 * @property string $system Berisi data yang nilainya memiliki format : nik, paspor, kk
 * @property string $value Berisi kode atau nomor pasien.
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property-read \App\Models\Patient\Patient|null $patient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientIdentifier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientIdentifier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientIdentifier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientIdentifier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientIdentifier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientIdentifier whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientIdentifier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientIdentifier whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientIdentifier wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientIdentifier whereSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientIdentifier whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientIdentifier whereUse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientIdentifier whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientIdentifier withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientIdentifier withoutTrashed()
 */
	class PatientIdentifier extends \Eloquent {}
}

namespace App\Models\PaymentMethod{
/**
 * 
 *
 * @property-read \App\Models\Account\Account|null $account
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction\TransactionPayment> $transactionPayments
 * @property-read int|null $transaction_payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod withoutTrashed()
 */
	class PaymentMethod extends \Eloquent {}
}

namespace App\Models\Poly{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poly newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poly newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poly onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poly query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poly search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poly withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poly withoutTrashed()
 */
	class Poly extends \Eloquent {}
}

namespace App\Models\Practitiont\OneHealth{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encounter\Onehealth\OneHealthEnconterParticipant> $OHEncounterParticipants
 * @property-read int|null $o_h_encounter_participants_count
 * @property-read \App\Models\Practitiont\OneHealth\OneHealthPractitiontAddress|null $OHPractitiontAddress
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Practitiont\OneHealth\OneHealthPractitiontIdentifier> $OHPractitiontIdentifiers
 * @property-read int|null $o_h_practitiont_identifiers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Practitiont\OneHealth\OneHealthPractitiontQualificationCodeCoding> $OHPractitiontQualificationCodeCodings
 * @property-read int|null $o_h_practitiont_qualification_code_codings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Practitiont\OneHealth\OneHealthPractitiontQualificationIndentifier> $OHPractitiontQualificationIdentifiers
 * @property-read int|null $o_h_practitiont_qualification_identifiers_count
 * @property-read \App\Models\Practitiont\Practitioner|null $practitioner
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitioner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitioner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitioner onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitioner query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitioner withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitioner withoutTrashed()
 */
	class OneHealthPractitioner extends \Eloquent {}
}

namespace App\Models\Practitiont\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Practitiont\OneHealth\OneHealthPractitioner|null $OHPractitiont
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Practitiont\OneHealth\OneHealthPractitiontAddressExtension> $extensions
 * @property-read int|null $extensions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontAddress onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontAddress withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontAddress withoutTrashed()
 */
	class OneHealthPractitiontAddress extends \Eloquent {}
}

namespace App\Models\Practitiont\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Practitiont\OneHealth\OneHealthPractitiontAddress|null $OHPractitiontAddress
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontAddressExtension newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontAddressExtension newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontAddressExtension onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontAddressExtension query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontAddressExtension withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontAddressExtension withoutTrashed()
 */
	class OneHealthPractitiontAddressExtension extends \Eloquent {}
}

namespace App\Models\Practitiont\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Practitiont\OneHealth\OneHealthPractitioner|null $OHPractitiont
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontIdentifier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontIdentifier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontIdentifier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontIdentifier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontIdentifier withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontIdentifier withoutTrashed()
 */
	class OneHealthPractitiontIdentifier extends \Eloquent {}
}

namespace App\Models\Practitiont\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Practitiont\OneHealth\OneHealthPractitioner|null $OHPractitiont
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontQualificationCodeCoding newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontQualificationCodeCoding newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontQualificationCodeCoding onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontQualificationCodeCoding query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontQualificationCodeCoding withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontQualificationCodeCoding withoutTrashed()
 */
	class OneHealthPractitiontQualificationCodeCoding extends \Eloquent {}
}

namespace App\Models\Practitiont\OneHealth{
/**
 * 
 *
 * @property-read \App\Models\Practitiont\OneHealth\OneHealthPractitioner|null $OHPractitiont
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontQualificationIndentifier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontQualificationIndentifier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontQualificationIndentifier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontQualificationIndentifier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontQualificationIndentifier withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OneHealthPractitiontQualificationIndentifier withoutTrashed()
 */
	class OneHealthPractitiontQualificationIndentifier extends \Eloquent {}
}

namespace App\Models\Practitiont{
/**
 * 
 *
 * @property-read \App\Models\Practitiont\OneHealth\OneHealthPractitioner|null $OHPractitioner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Encounter\EncounterPractitiont> $encounterPractitionts
 * @property-read int|null $encounter_practitionts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicationRequest\MedicationRequest> $requestMedicationReqs
 * @property-read int|null $request_medication_reqs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Practitioner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Practitioner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Practitioner onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Practitioner query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Practitioner withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Practitioner withoutTrashed()
 */
	class Practitioner extends \Eloquent {}
}

namespace App\Models\Product{
/**
 * 
 *
 * @property string $id
 * @property string $sku_number
 * @property string $name
 * @property string|null $description
 * @property string $product_category_id
 * @property string|null $product_factory_id
 * @property string|null $product_unit_id
 * @property string $product_rack_id
 * @property string $product_type_id
 * @property string $company_id
 * @property string|null $registration_path
 * @property bool|null $is_narcotics
 * @property bool|null $is_use_stock
 * @property int $medicine_dosage
 * @property string|null $dosage_unit
 * @property string|null $unit_id
 * @property int|null $minimun_stock
 * @property int|null $safety_stock
 * @property int|null $maximum_stock
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read mixed $name_sku
 * @property-read \App\Models\Product\ProductCategory|null $productCategory
 * @property-read \App\Models\Product\ProductFactory|null $productFactory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product\ProductPackage> $productPackages
 * @property-read int|null $product_packages_count
 * @property-read \App\Models\Product\ProductPrice|null $productPrice
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product\ProductPriceHistory> $productPriceHistories
 * @property-read int|null $product_price_histories_count
 * @property-read \App\Models\Product\ProductRack|null $productRack
 * @property-read \App\Models\Product\ProductStock|null $productStock
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product\ProductStockHistory> $productStockHistories
 * @property-read int|null $product_stock_histories_count
 * @property-read \App\Models\Product\ProductType|null $productType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product\ProductUnit> $productUnits
 * @property-read int|null $product_units_count
 * @property-read \App\Models\Unit\Unit|null $unit
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDosageUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsNarcotics($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsUseStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereMaximumStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereMedicineDosage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereMinimunStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductFactoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductRackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereRegistrationPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSafetyStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSkuNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withoutTrashed()
 */
	class Product extends \Eloquent {}
}

namespace App\Models\Product{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property int $normal
 * @property int $recipe
 * @property string $price
 * @property string $company_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereNormal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereRecipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory withoutTrashed()
 */
	class ProductCategory extends \Eloquent {}
}

namespace App\Models\Product{
/**
 * 
 *
 * @property string $id
 * @property string $product_stock_id
 * @property string $product_id
 * @property string $branch_id
 * @property string $expired_date
 * @property string $batch_number
 * @property int $quantity
 * @property string $company_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Branch\Branch|null $branch
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Product\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate whereBatchNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate whereExpiredDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate whereProductStockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductExpiredDate withoutTrashed()
 */
	class ProductExpiredDate extends \Eloquent {}
}

namespace App\Models\Product{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property string $company_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFactory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFactory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFactory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFactory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFactory search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFactory whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFactory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFactory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFactory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFactory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFactory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFactory whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFactory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFactory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductFactory withoutTrashed()
 */
	class ProductFactory extends \Eloquent {}
}

namespace App\Models\Product{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Product\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImportStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImportStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImportStock onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImportStock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImportStock withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImportStock withoutTrashed()
 */
	class ProductImportStock extends \Eloquent {}
}

namespace App\Models\Product{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Product\Product|null $product
 * @property-read \App\Models\Product\Product|null $productChild
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPackage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPackage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPackage withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPackage withoutTrashed()
 */
	class ProductPackage extends \Eloquent {}
}

namespace App\Models\Product{
/**
 * 
 *
 * @property string $id
 * @property string $product_id
 * @property string $branch_id
 * @property string $hpp_average
 * @property string $price
 * @property string $recipe
 * @property string $company_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch\Branch|null $branch
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Product\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice whereHppAverage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice whereRecipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPrice withoutTrashed()
 */
	class ProductPrice extends \Eloquent {}
}

namespace App\Models\Product{
/**
 * 
 *
 * @property string $id
 * @property string $product_id
 * @property string $branch_id
 * @property string $purchase_price
 * @property string $quantity
 * @property string $sub_total_price
 * @property string $hpp_average
 * @property string $company_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory whereHppAverage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory wherePurchasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory whereSubTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductPriceHistory withoutTrashed()
 */
	class ProductPriceHistory extends \Eloquent {}
}

namespace App\Models\Product{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property string $company_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductRack newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductRack newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductRack onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductRack query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductRack search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductRack whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductRack whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductRack whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductRack whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductRack whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductRack whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductRack whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductRack whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductRack withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductRack withoutTrashed()
 */
	class ProductRack extends \Eloquent {}
}

namespace App\Models\Product{
/**
 * 
 *
 * @property string $id
 * @property string $product_id
 * @property string $branch_id
 * @property string $quantity
 * @property string $company_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch\Branch|null $branch
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Product\Product|null $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product\ProductStockHistory> $productStockHistories
 * @property-read int|null $product_stock_histories_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStock withoutTrashed()
 */
	class ProductStock extends \Eloquent {}
}

namespace App\Models\Product{
/**
 * 
 *
 * @property string $id
 * @property string $product_id
 * @property string $branch_id
 * @property string $code
 * @property string $description
 * @property string|null $reference
 * @property string $date
 * @property string $quantity
 * @property string $type
 * @property string $price
 * @property string $sub_total_price
 * @property string $company_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch\Branch|null $branch
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Product\Product|null $product
 * @property-read \App\Models\Product\ProductStock|null $productStock
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory whereSubTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStockHistory withoutTrashed()
 */
	class ProductStockHistory extends \Eloquent {}
}

namespace App\Models\Product{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType withoutTrashed()
 */
	class ProductType extends \Eloquent {}
}

namespace App\Models\Product{
/**
 * 
 *
 * @property string $id
 * @property string $product_id
 * @property string $unit_id
 * @property int $quantity
 * @property string $company_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Product\Product|null $product
 * @property-read \App\Models\Unit\Unit|null $unit
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductUnit withoutTrashed()
 */
	class ProductUnit extends \Eloquent {}
}

namespace App\Models\Promotion{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion withoutTrashed()
 */
	class Promotion extends \Eloquent {}
}

namespace App\Models\Promotion{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromotionDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromotionDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromotionDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromotionDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromotionDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromotionDetail withoutTrashed()
 */
	class PromotionDetail extends \Eloquent {}
}

namespace App\Models\PurchaseOrder{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string $supplier_id
 * @property string|null $branch_id
 * @property string $status
 * @property string $price
 * @property string $discount
 * @property string $grant_total
 * @property string|null $note
 * @property string $company_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PurchaseOrder\PurchaseOrderItem> $purchaseOrderItems
 * @property-read int|null $purchase_order_items_count
 * @property-read \App\Models\PurchaseRequisition\PurchaseRequisition|null $purchaseRequisition
 * @property-read \App\Models\Supplier\Supplier|null $supplier
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereGrantTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder withoutTrashed()
 */
	class PurchaseOrder extends \Eloquent {}
}

namespace App\Models\PurchaseOrder{
/**
 * 
 *
 * @property string $id
 * @property string $purchase_order_id
 * @property string $product_id
 * @property string $quantity
 * @property string $price
 * @property string $hna
 * @property string $hna_ppn
 * @property string $subtotal
 * @property string $discount
 * @property string $total
 * @property string $company_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product\Product|null $product
 * @property-read \App\Models\Product\ProductUnit|null $productUnit
 * @property-read \App\Models\PurchaseOrder\PurchaseOrder|null $purchaseOrder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereHna($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereHnaPpn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem wherePurchaseOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem withoutTrashed()
 */
	class PurchaseOrderItem extends \Eloquent {}
}

namespace App\Models\PurchaseRequisition{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string $number
 * @property string $status
 * @property string $company_id
 * @property string|null $branch_id
 * @property string|null $supplier_id
 * @property string $grand_total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\PurchaseOrder\PurchaseOrder|null $purchaseOrder
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PurchaseRequisition\PurchaseRequisitionItem> $purchaseRequisitionItems
 * @property-read int|null $purchase_requisition_items_count
 * @property-read \App\Models\Supplier\Supplier|null $supplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisition withoutTrashed()
 */
	class PurchaseRequisition extends \Eloquent {}
}

namespace App\Models\PurchaseRequisition{
/**
 * 
 *
 * @property string $id
 * @property string|null $purchase_requisition_id
 * @property string|null $branch_id
 * @property string|null $company_id
 * @property string $product_id
 * @property string $product_name
 * @property string|null $unit_id
 * @property string|null $product_unit_id
 * @property int $quantity
 * @property int $quantity_detail
 * @property int $quantity_real
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Product\Product|null $product
 * @property-read \App\Models\Product\ProductUnit|null $productUnit
 * @property-read \App\Models\PurchaseRequisition\PurchaseRequisition|null $purchaseRequisition
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem whereProductUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem wherePurchaseRequisitionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem whereQuantityDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem whereQuantityReal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseRequisitionItem withoutTrashed()
 */
	class PurchaseRequisitionItem extends \Eloquent {}
}

namespace App\Models\PurchaseReturn{
/**
 * 
 *
 * @property-read \App\Models\Branch\Branch|null $branch
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\PurchaseOrder\PurchaseOrder|null $purchaseOrder
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PurchaseReturn\PurchaseReturnIndex> $purchaseReturnsItems
 * @property-read int|null $purchase_returns_items_count
 * @property-read \App\Models\Supplier\Supplier|null $supplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturn withoutTrashed()
 */
	class PurchaseReturn extends \Eloquent {}
}

namespace App\Models\PurchaseReturn{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Product\Product|null $product
 * @property-read \App\Models\Product\ProductUnit|null $productUnit
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnIndex newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnIndex newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnIndex onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnIndex query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnIndex withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReturnIndex withoutTrashed()
 */
	class PurchaseReturnIndex extends \Eloquent {}
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
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $price
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service wherePrice($value)
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

namespace App\Models\StockOpname{
/**
 * 
 *
 * @property-read \App\Models\Branch\Branch|null $branch
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Product\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryStockOpnameItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryStockOpnameItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryStockOpnameItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryStockOpnameItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryStockOpnameItem withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistoryStockOpnameItem withoutTrashed()
 */
	class HistoryStockOpnameItem extends \Eloquent {}
}

namespace App\Models\StockOpname{
/**
 * 
 *
 * @property-read \App\Models\User|null $approvedBy
 * @property-read \App\Models\Branch\Branch|null $branch
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockOpname\StockOpnameItem> $stockOpnameItems
 * @property-read int|null $stock_opname_items_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname withoutTrashed()
 */
	class StockOpname extends \Eloquent {}
}

namespace App\Models\StockOpname{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem withoutTrashed()
 */
	class StockOpnameItem extends \Eloquent {}
}

namespace App\Models\Supplier{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string|null $address
 * @property string|null $province
 * @property string|null $city
 * @property string|null $district
 * @property string|null $sub_district
 * @property string $company_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereSubDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier withoutTrashed()
 */
	class Supplier extends \Eloquent {}
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

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Icd\Icd10|null $icd10
 * @property-read \App\Models\Transaction\Transaction|null $transaction
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportingTransactionIcd10 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportingTransactionIcd10 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportingTransactionIcd10 onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportingTransactionIcd10 query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportingTransactionIcd10 withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportingTransactionIcd10 withoutTrashed()
 */
	class SupportingTransactionIcd10 extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property string $id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch\Branch|null $branch
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\User\ControlDoctor|null $controlDoctor
 * @property-read \App\Models\User|null $createdBy
 * @property-read \App\Models\User|null $doctor
 * @property-read \App\Models\Location\Location|null $location
 * @property-read \App\Models\User|null $patient
 * @property-read \App\Models\User\UserCompanyRole|null $patientCompanyRole
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction\TransactionDetail> $transactionDetails
 * @property-read int|null $transaction_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction\TransactionNurse> $transactionNurses
 * @property-read int|null $transaction_nurses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction\TransactionPayment> $transactionPayments
 * @property-read int|null $transaction_payments_count
 * @property-read \App\Models\Transaction\TransactionPhysicalExamination|null $transactionPhysicalExamination
 * @property-read \App\Models\Transaction\TransactionPrimary|null $transactionPrimary
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction\TransactionRecipe> $transactionRecipes
 * @property-read int|null $transaction_recipes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction withoutTrashed()
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Product\Product|null $product
 * @property-read \App\Models\Transaction\Transaction|null $transaction
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionAction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionAction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionAction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionAction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionAction withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionAction withoutTrashed()
 */
	class TransactionAction extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionActionDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionActionDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionActionDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionActionDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionActionDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionActionDetail withoutTrashed()
 */
	class TransactionActionDetail extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property string $id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Product\Product|null $product
 * @property-read \App\Models\Transaction\Transaction|null $transaction
 * @property-read \App\Models\Transaction\TransactionRecipe|null $transactionRecipe
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDetail whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDetail withoutTrashed()
 */
	class TransactionDetail extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDetailPackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDetailPackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDetailPackage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDetailPackage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDetailPackage withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDetailPackage withoutTrashed()
 */
	class TransactionDetailPackage extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDiagnosis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDiagnosis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDiagnosis onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDiagnosis query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDiagnosis withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDiagnosis withoutTrashed()
 */
	class TransactionDiagnosis extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Icd\Icd10|null $icd10
 * @property-read \App\Models\Transaction\Transaction|null $transaction
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionIcd10 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionIcd10 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionIcd10 onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionIcd10 query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionIcd10 withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionIcd10 withoutTrashed()
 */
	class TransactionIcd10 extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Icd\Icd9|null $icd9
 * @property-read \App\Models\Transaction\Transaction|null $transaction
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionIcd9 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionIcd9 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionIcd9 onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionIcd9 query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionIcd9 withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionIcd9 withoutTrashed()
 */
	class TransactionIcd9 extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionNurse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionNurse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionNurse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionNurse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionNurse withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionNurse withoutTrashed()
 */
	class TransactionNurse extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\PaymentMethod\PaymentMethod|null $paymentMethod
 * @property-read \App\Models\Transaction\Transaction|null $transaction
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPayment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPayment search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPayment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPayment withoutTrashed()
 */
	class TransactionPayment extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPhysicalExamination newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPhysicalExamination newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPhysicalExamination onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPhysicalExamination query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPhysicalExamination withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPhysicalExamination withoutTrashed()
 */
	class TransactionPhysicalExamination extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPrimary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPrimary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPrimary onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPrimary query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPrimary withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionPrimary withoutTrashed()
 */
	class TransactionPrimary extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Product\Product|null $product
 * @property-read \App\Models\Transaction\Transaction|null $transaction
 * @property-read \App\Models\Transaction\TransactionDetail|null $transactionDetail
 * @property-read \App\Models\Transaction\TransactionRecipe|null $transactionRecipe
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionProduct onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionProduct search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionProduct withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionProduct withoutTrashed()
 */
	class TransactionProduct extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionProofOfAction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionProofOfAction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionProofOfAction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionProofOfAction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionProofOfAction withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionProofOfAction withoutTrashed()
 */
	class TransactionProofOfAction extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\MedicineType\MedicineType|null $medicineType
 * @property-read \App\Models\Product\Product|null $product
 * @property-read \App\Models\Transaction\Transaction|null $transaction
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction\TransactionDetail> $transactionDetail
 * @property-read int|null $transaction_detail_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipe onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipe query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipe withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipe withoutTrashed()
 */
	class TransactionRecipe extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipeReal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipeReal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipeReal onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipeReal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipeReal withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipeReal withoutTrashed()
 */
	class TransactionRecipeReal extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipeRealDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipeRealDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipeRealDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipeRealDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipeRealDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionRecipeRealDetail withoutTrashed()
 */
	class TransactionRecipeRealDetail extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionReference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionReference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionReference onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionReference query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionReference withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionReference withoutTrashed()
 */
	class TransactionReference extends \Eloquent {}
}

namespace App\Models\Transaction{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionSecondary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionSecondary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionSecondary onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionSecondary query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionSecondary withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionSecondary withoutTrashed()
 */
	class TransactionSecondary extends \Eloquent {}
}

namespace App\Models\Unit{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit withoutTrashed()
 */
	class Unit extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $username
 * @property string $password
 * @property string|null $profile
 * @property string|null $company_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $order
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Company\Company> $companies
 * @property-read int|null $companies_count
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User\UserCompanyRole> $companyRoles
 * @property-read int|null $company_roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User\ControlDoctor> $controlDoctors
 * @property-read int|null $control_doctors_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Patient\Patient|null $patient
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Spatie\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Spatie\Role> $roles
 * @property-read int|null $roles_count
 * @property-read User|null $user
 * @property-read \App\Models\User\UserDetail|null $userDetail
 * @property-read \App\Models\User\UserPrice|null $userPrice
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
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
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllergyMedicine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllergyMedicine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllergyMedicine onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllergyMedicine query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllergyMedicine withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AllergyMedicine withoutTrashed()
 */
	class AllergyMedicine extends \Eloquent {}
}

namespace App\Models\User{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\Location\Location|null $location
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ControlDoctor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ControlDoctor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ControlDoctor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ControlDoctor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ControlDoctor search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ControlDoctor withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ControlDoctor withoutTrashed()
 */
	class ControlDoctor extends \Eloquent {}
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
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\User|null $doctor
 * @property-read \App\Models\Location\Location|null $location
 * @property-read \App\Models\Transaction\Transaction|null $transaction
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserControlSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserControlSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserControlSchedule onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserControlSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserControlSchedule withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserControlSchedule withoutTrashed()
 */
	class UserControlSchedule extends \Eloquent {}
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
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserIncentive newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserIncentive newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserIncentive onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserIncentive query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserIncentive search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserIncentive withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserIncentive withoutTrashed()
 */
	class UserIncentive extends \Eloquent {}
}

namespace App\Models\User{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserInsurance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserInsurance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserInsurance onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserInsurance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserInsurance withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserInsurance withoutTrashed()
 */
	class UserInsurance extends \Eloquent {}
}

namespace App\Models\User{
/**
 * 
 *
 * @property-read \App\Models\Company\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPrice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPrice withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPrice withoutTrashed()
 */
	class UserPrice extends \Eloquent {}
}

