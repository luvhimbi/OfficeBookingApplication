<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old index
        DB::statement("DROP INDEX IF EXISTS bookings_unique_slot");

        // Create new correct partial index INCLUDING the date
        DB::statement("
            CREATE UNIQUE INDEX bookings_unique_slot
            ON bookings (space_type, space_id, date, start_time, end_time)
            WHERE status != 'cancelled'
        ");
    }

    public function down(): void
    {
        // Revert back to old broken one if needed
        DB::statement("DROP INDEX IF EXISTS bookings_unique_slot");

        DB::statement("
            CREATE UNIQUE INDEX bookings_unique_slot
            ON bookings (space_type, space_id, start_time, end_time)
            WHERE status != 'cancelled'
        ");
    }
};
