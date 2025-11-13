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
        Schema::create('boardrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campus_id')->constrained('campuses')->onDelete('cascade');
            // Boardroom must belong to a building (required)
            $table->foreignId('building_id')->constrained('buildings')->onDelete('cascade');

            // Optional floor reference - must be a floor that belongs to the same building logically
            // We enforce referential integrity here; additional app-level checks can ensure floor belongs to building.
            $table->foreignId('floor_id')->nullable()->constrained('floors')->onDelete('set null');

            $table->string('name');        // e.g., "Boardroom A"
            $table->integer('capacity')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Unique boardroom name per building (prevents duplicates inside same building)
            $table->unique(['building_id', 'name'], 'boardrooms_building_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boardrooms');
    }
};
