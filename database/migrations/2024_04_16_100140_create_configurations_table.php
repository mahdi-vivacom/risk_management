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
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->string ( 'support_mail' )->nullable ();
            $table->string ( 'support_phone' )->nullable ();
            $table->string ( 'google_key' )->nullable ();
            $table->string ( 'distance_unit' )->nullable ();
            $table->string ( 'splash_screen' )->nullable ();
            $table->tinyInteger ( 'status' )->default ( 1 )->comment ( '1: Active, 0: Deactive' );
            $table->timestamps();
            $table->softDeletes ();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
