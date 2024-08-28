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
        Schema::create('skill_areas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger ( 'country_area_id' )->nullable ();
            $table->unsignedBigInteger ( 'skill_id' )->nullable ();
            $table->timestamps();
            
            $table->foreign ( 'country_area_id' )->references ( 'id' )->on ( 'country_areas' )->onDelete ( 'set null' );
            $table->foreign ( 'skill_id' )->references ( 'id' )->on ( 'skills' )->onDelete ( 'set null' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_areas');
    }
};
