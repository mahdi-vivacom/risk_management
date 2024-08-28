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
        Schema::create('sos_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger ( 'booking_id' );
            $table->unsignedBigInteger ( 'country_area_id' );
            $table->string ( 'number' )->nullable ();
            $table->string ( 'latitude' )->nullable ();
            $table->string ( 'longitude' )->nullable ();
            $table->tinyInteger ( 'status' )->default ( 1 );
            $table->tinyInteger ( 'application' )->nullable ();
            $table->timestamps ();
            $table->softDeletes ();
            $table->foreign ( 'booking_id' )->references ( 'id' )->on ( 'bookings' );
            $table->foreign ( 'country_area_id' )->references ( 'id' )->on ( 'country_areas' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sos_requests');
    }
};
