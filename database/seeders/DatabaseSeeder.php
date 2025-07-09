<?php

namespace Database\Seeders;

use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDosagePeriodUnit;
use App\Models\Master\CodeSystem\Patient\ContactPointSystem;
use Database\Seeders\Account\AccountSeeder;
use Database\Seeders\Account\CategoryAccountSeeder;
use Database\Seeders\Account\DetailCategoryAccountSeeder;
use Database\Seeders\CodeSystem\Condition\CategorySeeder as Condition_CategorySeeder;
use Database\Seeders\CodeSystem\Condition\ClinicalStatusSeeder as Condition_ClinicalStatusSeeder;
use Database\Seeders\CodeSystem\Condition\CodeChiefComplaint as Condition_CodeChiefComplaint;
use Database\Seeders\CodeSystem\Condition\SeveritySeeder as Condition_SeveritySeeder;
use Database\Seeders\CodeSystem\Condition\VerificationStatusSeeder as Condition_VerificationStatusSeeder;
use Database\Seeders\CodeSystem\Encounter\ActCodeSeeder as Encounter_ActCodeSeeder;
use Database\Seeders\CodeSystem\Encounter\ActPrioritySeeder as Encounter_ActPrioritySeeder;
use Database\Seeders\CodeSystem\Encounter\DiagnosisRoleSeeder as Encounter_DiagnosisRoleSeeder;
use Database\Seeders\CodeSystem\Encounter\EncounterStatusSeeder as Encounter_EncounterStatusSeeder;
use Database\Seeders\CodeSystem\Encounter\EncounterTypeSeeder as Encounter_EncounterTypeSeeder;
use Database\Seeders\CodeSystem\Encounter\IdentifierUseSeeder as Encounter_IdentifierUseSeeder;
use Database\Seeders\CodeSystem\Encounter\ParticipantTypeSeeder as Encounter_ParticipantTypeSeeder;
use Database\Seeders\CodeSystem\Encounter\ServiceTypeSeeder as Encounter_ServiceTypeSeeder;
use Database\Seeders\CodeSystem\Encounter\SystemTypeSeeder;
use Database\Seeders\CodeSystem\Location\AddressUseSeeder as Location_AddressUseSeeder;
use Database\Seeders\CodeSystem\Location\ContactPointSystemSeeder as Location_ContactPointSystemSeeder;
use Database\Seeders\CodeSystem\Location\ContactPointUseSeeder as Location_ContactPointUseSeeder;
use Database\Seeders\CodeSystem\Master\General\AddressTypeSeeder;
use Database\Seeders\CodeSystem\Organization\AddressTypeSeeder as Organization_AddressTypeSeeder;
use Database\Seeders\CodeSystem\Organization\AddressUseSeeder as Organization_AddressUseSeeder;
use Database\Seeders\CodeSystem\Organization\ContactPointSystemSeeder as Organization_ContactPointSystemSeeder;
use Database\Seeders\CodeSystem\Organization\ContactPointUseSeeder as Organization_ContactPointUseSeeder;
use Database\Seeders\CodeSystem\Organization\IdentifierUseSeeder as Organization_IdentifierUseSeeder;
use Database\Seeders\CodeSystem\Organization\OrganizationTypeSeeder as Organization_TypeSeeder;
use Database\Seeders\CodeSystem\Location\IdentifierUseSeeder as Location_IdentifierUseSeeder;
use Database\Seeders\CodeSystem\Location\LocationModeSeeder as Location_LocationModeSeeder;
use Database\Seeders\CodeSystem\Location\LocationStatusSeeder as Location_LocationStatusSeeder;
use Database\Seeders\CodeSystem\Location\LocationTypeSeeder as Location_TypeSeeder;
use Database\Seeders\CodeSystem\Medication\FormSeeder as Medication_FormSeeder;
use Database\Seeders\CodeSystem\Medication\IdentifierUseSeeder as Medication_IdentifierUseSeeder;
use Database\Seeders\CodeSystem\Medication\MedicationTypeSeeder as Medication_MedicationTypeSeeder;
use Database\Seeders\CodeSystem\Medication\OrderableDrugFormSeeder as Medication_OrderableDrugFormSeeder;
use Database\Seeders\CodeSystem\Medication\StatusSeeder as Medication_StatusSeeder;
use Database\Seeders\CodeSystem\Medication\ValueQuantitySeeder as Medication_ValueQuantitySeeder;
use Database\Seeders\CodeSystem\MedicationDispense\CategorySeeder as MedicationDispense_CategorySeeder;
use Database\Seeders\CodeSystem\MedicationDispense\DaysSupplySeeder as MedicationDispense_DaysSupplySeeder;
use Database\Seeders\CodeSystem\MedicationDispense\DosageDoseRateSeeder as MedicationDispense_DosageDoseRateSeeder;
use Database\Seeders\CodeSystem\MedicationDispense\DosagePeriodUnitSeeder as MedicationDispense_DosagePeriodUnitSeeder;
use Database\Seeders\CodeSystem\MedicationDispense\IdentifierUseSeeder as MedicationDispense_IdentifierUseSeeder;
use Database\Seeders\CodeSystem\MedicationDispense\OrderableDrugFormSeeder as MedicationDispense_OrderableDrugFormSeeder;
use Database\Seeders\CodeSystem\MedicationDispense\StatusSeeder as MedicationDispense_StatusSeeder;
use Database\Seeders\CodeSystem\MedicationDispense\ValueQuantitySeeder as MedicationDispense_ValueQuantitySeeder;
use Database\Seeders\CodeSystem\MedicationRequest\CategorySeeder as MedicationRequest_CategorySeeder;
use Database\Seeders\CodeSystem\MedicationRequest\CourseOfTherapySeeder as MedicationRequest_CourseOfTherapySeeder;
use Database\Seeders\CodeSystem\MedicationRequest\DispenseExpectSeeder as MedicationRequest_DispenseExpectSeeder;
use Database\Seeders\CodeSystem\MedicationRequest\DispenseIntervalSeeder as MedicationRequest_DispenseIntervalSeeder;
use Database\Seeders\CodeSystem\MedicationRequest\DosageDoseRateSeeder as MedicationRequest_DosageDoseRateSeeder;
use Database\Seeders\CodeSystem\MedicationRequest\DosageDurationUnitSeeder as MedicationRequest_DosageDurationUnitSeeder;
use Database\Seeders\CodeSystem\MedicationRequest\DosagePeriodUnitSeeder as MedicationRequest_DosagePeriodUnitSeeder;
use Database\Seeders\CodeSystem\MedicationRequest\DosageRouteSeeder as MedicationRequest_DosageRouteSeeder;
use Database\Seeders\CodeSystem\MedicationRequest\IdentifierUseSeeder as MedicationRequest_IdentifierUseSeeder;
use Database\Seeders\CodeSystem\MedicationRequest\IntentSeeder as MedicationRequest_IntentSeeder;
use Database\Seeders\CodeSystem\MedicationRequest\OrderableDrugFormSeeder as MedicationRequest_OrderableDrugFormSeeder;
use Database\Seeders\CodeSystem\MedicationRequest\PrioritySeeder as MedicationRequest_PrioritySeeder;
use Database\Seeders\CodeSystem\MedicationRequest\StatusSeeder as MedicationRequest_StatusSeeder;
use Database\Seeders\CodeSystem\MedicationRequest\ValueQuantitySeeder as MedicationRequest_ValueQuantitySeeder;
use Database\Seeders\CodeSystem\Observation\CategorySeeder as Observation_CategorySeeder;
use Database\Seeders\CodeSystem\Observation\CodeSeeder as Observation_CodeSeeder;
use Database\Seeders\CodeSystem\Observation\StatusSeeder as Observation_StatusSeeder;
use Database\Seeders\CodeSystem\Patient\AddressUseSeeder as Patient_AddressUseSeeder;
use Database\Seeders\CodeSystem\Patient\AdministrativeGenderSeeder as Patient_AdministrativeGenderSeeder;
use Database\Seeders\CodeSystem\Patient\ContactPointSystemSeeder as Patient_ContactPointSystem;
use Database\Seeders\CodeSystem\Patient\ContactPointUseSeeder as Patient_ContactPointUseSeeder;
use Database\Seeders\CodeSystem\Patient\ContactRelationshipSeeder as Patient_ContactRelationshipSeeder;
use Database\Seeders\CodeSystem\Patient\IdentifierUseSeeder as Patient_IdentifierUseSeeder;
use Database\Seeders\CodeSystem\Patient\MaritalStatusSeeder as Patient_MaritalStatusSeeder;
use Database\Seeders\Company\CompanySeeder;
use Database\Seeders\Consultation\MasterConsultationCategoryConditionSeeder;
use Database\Seeders\Consultation\MasterConsultationConditionClinicalSeeder;
use Database\Seeders\Consultation\MasterConsultationConditionVerStatusSeeder;
use Database\Seeders\Consultation\MasterConsultationSnomedCTSeeder;
use Database\Seeders\Consultation\MasterConsultationTerminologySeeder;
use Database\Seeders\Country\CountrySeeder;
use Database\Seeders\Icd\IcdSeeder;
use Database\Seeders\Region\CitySeeder;
use Database\Seeders\Region\DistrictSeeder;
use Database\Seeders\Region\ProvinceSeeder;
use Database\Seeders\Region\SubDistrictSeeder;
use Database\Seeders\Service\ServiceMonthSeeder;
use Database\Seeders\Service\ServiceSeeder;
use Database\Seeders\Type\TypeSeeder;
use Database\Seeders\Unit\UnitSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('optimize:clear');

        $this->call([
            ServiceSeeder::class,
            ServiceMonthSeeder::class,

            // EncounterStatusSeeder::class,
            // ActCodeSeeder::class,
            // EncounterTypeSeeder::class,
            // ServiceTypeSeeder::class,
            // ActPrioritySeeder::class,
            // ParticipationTypeSeeder::class,

            // Region
            CountrySeeder::class,
            // ProvinceSeeder::class,
            // CitySeeder::class,
            // DistrictSeeder::class,
            // SubDistrictSeeder::class,

            //Organization
            Organization_IdentifierUseSeeder::class,
            Organization_TypeSeeder::class,
            Organization_ContactPointSystemSeeder::class,
            Organization_ContactPointUseSeeder::class,
            Organization_AddressUseSeeder::class,
            Organization_AddressTypeSeeder::class,

            // Location
            Location_IdentifierUseSeeder::class,
            Location_LocationStatusSeeder::class,
            Location_LocationModeSeeder::class,
            Location_ContactPointSystemSeeder::class,
            Location_ContactPointUseSeeder::class,
            Location_AddressUseSeeder::class,
            Location_TypeSeeder::class,

            //Patient
            Patient_IdentifierUseSeeder::class,
            Patient_AdministrativeGenderSeeder::class,
            Patient_AddressUseSeeder::class,
            Patient_MaritalStatusSeeder::class,
            Patient_ContactRelationshipSeeder::class,
            Patient_ContactPointSystem::class,
            Patient_ContactPointUseSeeder::class,

            //Encounter
            Encounter_EncounterStatusSeeder::class,
            Encounter_IdentifierUseSeeder::class,
            Encounter_ActCodeSeeder::class,
            Encounter_EncounterTypeSeeder::class,
            Encounter_ServiceTypeSeeder::class,
            Encounter_ActPrioritySeeder::class,
            Encounter_ParticipantTypeSeeder::class,
            Encounter_DiagnosisRoleSeeder::class,

            //Condition
            Condition_ClinicalStatusSeeder::class,
            Condition_CategorySeeder::class,
            Condition_VerificationStatusSeeder::class,
            Condition_SeveritySeeder::class,
            Condition_CodeChiefComplaint::class,

            //Medication
            Medication_StatusSeeder::class,
            Medication_FormSeeder::class,
            Medication_IdentifierUseSeeder::class,
            Medication_OrderableDrugFormSeeder::class,
            Medication_ValueQuantitySeeder::class,
            Medication_MedicationTypeSeeder::class,

            //MedicationRequest
            MedicationRequest_IdentifierUseSeeder::class,
            MedicationRequest_StatusSeeder::class,
            MedicationRequest_IntentSeeder::class,
            MedicationRequest_CategorySeeder::class,
            MedicationRequest_PrioritySeeder::class,
            MedicationRequest_CourseOfTherapySeeder::class,
            MedicationRequest_DosageDurationUnitSeeder::class,
            MedicationRequest_DosagePeriodUnitSeeder::class,
            MedicationRequest_DosageDoseRateSeeder::class,
            MedicationRequest_DosageRouteSeeder::class,
            MedicationRequest_OrderableDrugFormSeeder::class,
            MedicationRequest_ValueQuantitySeeder::class,
            MedicationRequest_DispenseIntervalSeeder::class,
            MedicationRequest_DispenseExpectSeeder::class,

            //MedicationDispense
            MedicationDispense_IdentifierUseSeeder::class,
            MedicationDispense_StatusSeeder::class,
            MedicationDispense_CategorySeeder::class,
            MedicationDispense_OrderableDrugFormSeeder::class,
            MedicationDispense_ValueQuantitySeeder::class,
            MedicationDispense_DaysSupplySeeder::class,
            MedicationDispense_DosagePeriodUnitSeeder::class,
            MedicationDispense_DosageDoseRateSeeder::class,

            //Observation
            // Observation_StatusSeeder::class,
            // Observation_CategorySeeder::class,
            // Observation_CodeSeeder::class,

            // Company
            CompanySeeder::class,

            // Master
            DetailCategoryAccountSeeder::class,
            // CategoryAccountSeeder::class,
            // AccountSeeder::class,
            UnitSeeder::class,
            TypeSeeder::class,
            IcdSeeder::class,
        ]);
    }
}
