<?php

namespace App\Services\Module;

use App\Models\Master\Question\Module;

class ModuleService
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
        $material = Module::updateOrCreate(
            [
                'id' => $request['id'] ?? null
            ],
            [
                'user_id'          => $request['user_id'] ?? null,
                'company_id'       => $request['company_id'] ?? null,
                'question_type_id' => $request['question_type_id'] ?? null,
                'name'             => $request['name'] ?? null,
                'duration'         => $request['duration'] ?? null,
                'random_question'  => $request['random_question'] ?? false,
                'description'      => $request['description'] ?? null,
                'studys'           => json_encode($request['studys']) ?? json_encode([]),
            ]
        );

        return $material;
    }

    public function delete($id)
    {
        $result = Module::findOrFail($id);
        $result->delete();
    }
}
