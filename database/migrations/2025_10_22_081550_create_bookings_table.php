<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('campus_id')->constrained()->onDelete('cascade');
            $table->foreignId('building_id')->constrained()->onDelete('cascade');
            $table->foreignId('floor_id')->nullable()->constrained()->onDelete('cascade');

            // Flexible space type
            $table->enum('space_type', ['desk', 'boardroom']);
            $table->unsignedBigInteger('space_id'); // desk_id or boardroom_id

            $table->dateTime('start_time');
            $table->dateTime('end_time');

            $table->enum('status', ['booked', 'cancelled', 'completed'])->default('booked');

//            $table->boolean('is_recurring')->default(false);
//            $table->string('recurring_day')->nullable(); // e.g., 'Wednesday'
//            $table->date('recurring_until')->nullable(); // end date (one month later)
//            $table->foreignId('parent_booking_id')->nullable()->constrained('bookings')->onDelete('cascade');

            $table->timestamps();

            // Prevent duplicate bookings of same space and time
            $table->unique(['space_type', 'space_id', 'start_time', 'end_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
