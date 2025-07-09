<?php

namespace App\Services\System\Location;

use App\Models\Location\Location;

class LocationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function updateOrCreateLocation($validatedData)
    {
        $location = Location::updateOrCreate(
            [
                'id'         => $validatedData['id'] ?? null,
            ],
            [
                'company_id'    => $validatedData['company_id'] ?? null,
                'location_id'   => $validatedData['location_id'] ?? null,
                'name'          => $validatedData['name'] ?? null,
                'description'   => $validatedData['description'] ?? null,
                'status'        => $validatedData['status'] ?? null,
                'mode'          => $validatedData['mode'] ?? null,
                'physical_type' => $validatedData['physical_type'] ?? null,
            ]
        );

        return $location;
    }
}
