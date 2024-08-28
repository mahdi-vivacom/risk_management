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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string ( 'name');
            $table->string ( 'description' )->nullable ();
            $table->string ( 'iso_code_2' )->nullable ();
            $table->string ( 'iso_code_3' )->nullable ();
            $table->string ( 'phone_code' )->nullable ();
            $table->integer ( 'distance_unit' )->default ( 1 )->comment ( '1:kilometer, 2:mile' );
            $table->tinyInteger ( 'status' )->default ( 1 );
            $table->timestamps ();
            $table->softDeletes ();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
