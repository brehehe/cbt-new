<?php

namespace App\Services\MaterialCategory;

use App\Models\Master\Question\MaterialCategory;

class MaterialCategoryService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function updateOrCreate($request)
    {
        $material_category = MaterialCategory::updateOrCreate(
            [
                'id' => $request['id'] ?? null,
            ],
            [
                'company_id' => $request['company_id'] ?? null,
                'topic_id' => $request['topic_id'] ?? null,
                'material_category_id' => $request['material_category_id'] ?? null,
                'name' => $request['name'] ?? null,
                'description' => $request['description'] ?? null,
            ]
        );

        return $material_category;
    }

    public function delete($id)
    {
        $result = MaterialCategory::findOrFail($id);
        $result->delete();
    }
}
