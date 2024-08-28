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
        Schema::create('subscription_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger ( 'package_id' )->nullable ();
            $table->unsignedBigInteger ( 'professional_id' )->nullable ();
            $table->unsignedBigInteger ( 'area_id' )->nullable ();
            $table->tinyInteger ( 'auto_renewal' )->default ( 0 );
            $table->dateTime ( 'validate_till' );
            $table->tinyInteger ( 'status' )->default ( 1 );
            $table->timestamps();
            $table->foreign ( 'package_id' )->references ( 'id' )->on ( 'subscriptions' )->onDelete ( 'set null' );
            $table->foreign ( 'professional_id' )->references ( 'id' )->on ( 'professionals' )->onDelete ( 'set null' );
            $table->foreign ( 'area_id' )->references ( 'id' )->on ( 'country_areas' )->onDelete ( 'set null' );

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_histories');
    }
};
