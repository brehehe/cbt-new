<?php

namespace App\Services\System\Medication;

use App\Models\Medication\Medication;
use App\Traits\Encryption;

class MedicationService
{
    use Encryption;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function updateOrCreateMedication($request)
    {
        $medication = Medication::updateOrCreate(
            [
                'id' => $request?->id
            ],
            [
                'product_id'             => $request?->product_id,
                'company_id'             => $request?->company_id,
                'code_coding_code'       => $request?->code_coding_code,
                'code_coding_display'    => $request?->code_coding_display,
                'status'                 => $request?->status,
                'manufacturer_reference' => $this->encrypted($request?->manufacturer_reference),
                'form_coding_code'       => $request?->form_coding_code,
                'medication_type_code'   => $request?->medication_type_code,
            ]
        );
        $item_codes = collect($request?->ingredients)->pluck('item_code')->toArray();

        $medication->medicationIngredients()->whereNotIn('item_coding_code', $item_codes)->delete();

        foreach ($request?->ingredients ?? [] as $key => $value) {
            $medication->medicationIngredients()->updateOrCreate(
                [
                    'item_coding_code' => $value['item_code'],
                ],
                [
                    'product_id'                 => $value['product_id'],
                    'item_coding_display'        => $value['item_display'],
                    'is_active'                  => $value['is_active'],
                    'strength_numerator_value'   => $value['numerator_value'],
                    'strength_numerator_code'    => $value['numerator_code'],
                    'strength_denominator_value' => $value['denominator_value'],
                    'strength_denominator_code'  => $value['denominator_code'],
                ]
            );
        }

        return $medication;
    }

    public function updateOrCreateOHMedication($medication)
    {
        $medication->refresh();
        $OHMedication = $medication->OHMedication()->updateOrCreate(
            [
                'medication_id' => $medication?->id
            ],
            [
                'status'                       => $medication?->status,
                'manufacturer_reference'       => $medication?->manufacturer_reference,
                // 'extention_medication_type'    => $medication?->medication_type_code,
                // 'extention_medication_display' => $medication?->medication_type_display,
            ]
        );

        $OHMedication->OHMedicationCode()->updateOrCreate(
            [
                'one_health_medication_id' => $OHMedication?->id
            ],
            [
                'coding_code'    => $medication?->code_coding_code,
                'coding_display' => $medication?->code_coding_display,
            ]
        );

        $OHMedication->OHMedicationForm()->updateOrCreate(
            [
                'one_health_medication_id' => $OHMedication?->id
            ],
            [
                'code'    => $medication->form_coding_code,
                'display' => $medication->form_coding_display,
            ]
        );

        $OHMedication->OHIngredients()->whereNotIn('medication_ingredient_id', $medication->medicationIngredients()->pluck('id')->toArray())->delete();
        $medicationIngredients = $medication->medicationIngredients()->select('id', 'item_coding_code', 'item_coding_display', 'is_active', 'strength_numerator_value', 'strength_numerator_code', 'strength_denominator_value', 'strength_denominator_code')->get();

        foreach ($medicationIngredients as $key => $ingredient) {
            // dd($ingredient);
            $OHMedication->OHIngredients()->updateOrCreate(
                [
                    'medication_ingredient_id' => $ingredient?->id
                ],
                [
                    'item_coding_code'           => $ingredient?->item_coding_code,
                    'item_coding_display'        => $ingredient?->item_coding_display,
                    'is_active'                  => $ingredient?->is_active,
                    'strength_numerator_value'   => $ingredient?->strength_numerator_value,
                    'strength_numerator_code'    => $ingredient?->strength_numerator_code,
                    'strength_denominator_value' => $ingredient?->strength_denominator_value,
                    'strength_denominator_code'  => $ingredient?->strength_denominator_code,
                ]
            );
        }

        $OHMedication->OHExtension()->updateOrCreate(
            [
                'one_health_medication_id' => $OHMedication?->id
            ],
            [
                'value_coding_code'    => $medication?->medication_type_code,
                'value_coding_display' => $medication?->medication_type_display,
            ]
        );

        return $OHMedication;
    }
}
