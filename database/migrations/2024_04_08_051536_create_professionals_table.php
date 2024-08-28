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
        Schema::create('professionals', function (Blueprint $table) {
            $table->id();
            $table->string ( 'phone_number' );
            $table->integer ( 'gender' )->nullable ();
            $table->string ( 'first_name' )->nullable ();
            $table->string ( 'last_name' )->nullable ();
            $table->string ( 'email' )->nullable ();
            $table->string ( 'password' );
            $table->string ( 'address' )->nullable ();
            $table->string ( 'profile_image' )->nullable ();
            $table->string ( 'wallet_money' )->nullable ();
            $table->string ( 'total_earnings' )->nullable ();
            $table->string ( 'current_latitude' )->nullable ();
            $table->string ( 'current_longitude' )->nullable ();
            $table->dateTime ( 'last_location_update_time' )->nullable ();
            $table->string ( 'bearing' )->nullable ();
            $table->string ( 'accuracy' )->nullable ();
            $table->tinyInteger ( 'device' )->nullable ()->comment ( '1:Android, 2:iOS ' );
            $table->string ( 'rating' )->default ( '1' );
            $table->unsignedBigInteger ( 'country_id' )->nullable ();
            $table->unsignedBigInteger ( 'country_area_id' )->nullable ();
            $table->integer ( 'login_logout' )->default ( 2 );
            $table->integer ( 'online_offline' )->default ( 2 )->comment ( '1:Online, 2:Offline ' );
            $table->integer ( 'free_busy' )->default ( 1 )->comment ( '1:Available, 0:Unavailable ' );
            $table->integer ( 'signup_from' )->default ( 1 );
            $table->integer ( 'signup_step' )->default ( 1 );
            $table->string ( 'online_code' )->nullable ();
            $table->string ( 'referral_code' )->nullable ();
            $table->text ( 'admin_msg' )->nullable ();
            $table->date ( 'dob' )->nullable ();
            $table->tinyInteger ( 'app_debug_mode' )->default ( 2 );
            $table->text ( 'segment_id' )->nullable ();
            $table->double ( 'reward_points' )->default ( 0 );
            $table->timestamp ( 'is_suspended' )->nullable ();
            $table->unsignedBigInteger ( 'referred_by' )->nullable ();
            $table->string ( 'device_ip' )->nullable ()->comment ( 'ip while registering' );
            $table->timestamps ();
            $table->softDeletes ();

            $table->foreign ( 'country_id' )->references ( 'id' )->on ( 'countries' )->onDelete ( 'set null' );
            $table->foreign ( 'country_area_id' )->references ( 'id' )->on ( 'country_areas' )->onDelete ( 'set null' );
            $table->foreign ( 'referred_by' )->references ( 'id' )->on ( 'professionals' )->onDelete ( 'set null' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professionals');
    }
};
