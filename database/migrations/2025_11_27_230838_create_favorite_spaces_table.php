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
        Schema::create('favorite_spaces', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('campus_id')->nullable();
            $table->unsignedBigInteger('building_id')->nullable();
            $table->unsignedBigInteger('floor_id')->nullable();

            $table->string('space_type')->nullable();
            $table->unsignedBigInteger('space_id');

            $table->unsignedBigInteger('booked_count')->default(0);

            $table->timestamps();

            // A user cannot have duplicate favorite space entries
            $table->unique([
                'user_id',
                'campus_id',
                'building_id',
                'floor_id',
                'space_type',
                'space_id'
            ], 'favorite_spaces_unique');

            // If you want cascading (optional depending on your schema)
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
             $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('set null');
             $table->foreign('building_id')->references('id')->on('buildings')->onDelete('set null');
             $table->foreign('floor_id')->references('id')->on('floors')->onDelete('set null');
        });





    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_spaces');
    }
};
