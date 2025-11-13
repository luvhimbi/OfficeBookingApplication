<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('available_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campus_id')->constrained()->onDelete('cascade');
            $table->foreignId('building_id')->constrained()->onDelete('cascade');
            $table->foreignId('floor_id')->constrained()->onDelete('cascade');
            $table->string('space_type'); // 'room', 'desk', 'parking', etc.
            $table->foreignId('space_id'); // Polymorphic relation
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_recurring')->default(false);
            $table->string('recurring_day')->nullable(); // 'monday', 'tuesday', etc.
            $table->date('recurring_until')->nullable();
            $table->integer('max_capacity')->default(1);
            $table->integer('booked_count')->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            // Index for efficient queries
            $table->index(['space_type', 'space_id', 'date', 'start_time']);
            $table->unique(['space_type', 'space_id', 'date', 'start_time'], 'slot_unique');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
