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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger ( 'country_id' )->nullable ();
            $table->unsignedBigInteger ( 'country_area_id' )->nullable ();
            $table->string ( 'first_name' )->nullable ();
            $table->string ( 'last_name' )->nullable ();
            $table->string ( 'phone_number' );
            $table->string ( 'email' )->nullable ();
            $table->string ( 'password' )->nullable ();
            $table->tinyInteger ( 'type' )->nullable ();
            $table->tinyInteger ( 'gender' )->nullable ()->comment ( '1: Male, 2: Female, 3:Others' );
            $table->tinyInteger ( 'status' )->default ( '1' );
            $table->string ( 'current_latitude' )->nullable ();
            $table->string ( 'current_longitude' )->nullable ();
            $table->integer ( 'otp' )->nullable ();
            $table->string ( 'referral_code' )->nullable ();
            $table->string ( 'average_rating' )->default ( '0.0' );
            $table->string ( 'email_verified' )->default ( '0' );
            $table->string ( 'phone_verified' )->default ( '0' );
            $table->string ( 'profile_image' )->nullable ();
            $table->double ( 'reward_points' )->nullable ();
            $table->rememberToken ();
            $table->timestamps ();
            $table->softDeletes ();

            $table->foreign ( 'country_id' )->references ( 'id' )->on ( 'countries' )->onDelete ( 'set null' );
            $table->foreign ( 'country_area_id' )->references ( 'id' )->on ( 'country_areas' )->onDelete ( 'set null' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
