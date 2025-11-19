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
            $table->unsignedBigInteger('space_id');


            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');

            $table->enum('status', ['booked', 'cancelled', 'completed'])->default('booked');

            $table->timestamps();


        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
