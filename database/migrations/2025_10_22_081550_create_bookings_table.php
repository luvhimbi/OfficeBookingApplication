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
            // Polymorphic relation to desk or boardroom
            $table->morphs('bookable');

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('campus_id')->constrained()->onDelete('cascade');
            $table->foreignId('building_id')->constrained()->onDelete('cascade');
            $table->foreignId('floor_id')->nullable()->constrained()->onDelete('cascade');

            // Flexible space type
            $table->enum('space_type', ['desk', 'boardroom']);
            $table->unsignedBigInteger('space_id');
            $table->enum('status', ['booked', 'cancelled', 'completed'])->default('booked');

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
