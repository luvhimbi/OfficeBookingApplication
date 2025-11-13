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

        Schema::create('desks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campus_id')->constrained('campuses')->onDelete('cascade');
            // Desk belongs to a building
            $table->foreignId('building_id')->constrained('buildings')->onDelete('cascade');

            // Desk may optionally belong to a floor
            $table->foreignId('floor_id')->nullable()->constrained('floors')->onDelete('set null');

            $table->string('desk_number');
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Unique desk number within a building
            $table->unique(['building_id', 'desk_number'], 'unique_desk_number_per_building');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desks');
    }
};
