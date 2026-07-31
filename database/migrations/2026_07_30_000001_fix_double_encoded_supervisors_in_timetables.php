<?php

use App\Models\Master\Timetable\Timetable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            $timetables = Timetable::withoutGlobalScopes()->whereNotNull('supervisors')->get();

            foreach ($timetables as $timetable) {
                $raw = $timetable->getRawOriginal('supervisors');
                if (empty($raw)) {
                    continue;
                }

                $value = $raw;
                $decoded = is_string($value) ? json_decode($value, true) : $value;

                // Loop in case of multiple levels of json_encoding
                $attempts = 0;
                while (is_string($decoded) && $attempts < 5) {
                    $next = json_decode($decoded, true);
                    if ($next === null && json_last_error() !== JSON_ERROR_NONE) {
                        break;
                    }
                    $decoded = $next;
                    $attempts++;
                }

                if (is_array($decoded)) {
                    $timetable->supervisors = array_values($decoded);
                    $timetable->save();
                }
            }
        } catch (\Throwable $th) {
            Log::error('Migration fix_double_encoded_supervisors_in_timetables failed: ' . $th->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed for down migration
    }
};
