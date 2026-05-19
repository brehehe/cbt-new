<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (config('database.default') !== 'pgsql') {
            return;
        }

        $connection = config('database.default');
        $dbUser = config("database.connections.{$connection}.username");

        DB::connection($connection)->unprepared("
            GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO {$dbUser};
            GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO {$dbUser};

            ALTER DEFAULT PRIVILEGES IN SCHEMA public
            GRANT ALL ON TABLES TO {$dbUser};

            ALTER DEFAULT PRIVILEGES IN SCHEMA public
            GRANT ALL ON SEQUENCES TO {$dbUser};
        ");
    }

    public function down(): void
    {
        //
    }
};