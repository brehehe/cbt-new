<?php

namespace App\Services\Material;

use App\Models\Master\Question\Material;

class MaterialService
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
        $material = Material::updateOrCreate(
            [
                'id' => $request['id'] ?? null
            ],
            [
                'company_id'           => $request['company_id'] ?? null,
                'material_category_id' => $request['material_category_id'] ?? null,
                'name'                 => $request['name'] ?? null,
                'level'                => $request['level'] ?? null,
                'description'          => $request['description'] ?? null,
            ]
        );

        return $material;
    }

    public function delete($id)
    {
        $result = Material::findOrFail($id);
        $result->delete();
    }
}
