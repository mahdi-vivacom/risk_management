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
        Schema::create('country_areas', function (Blueprint $table) {
            $table->id();
            $table->string ( 'name' );
            $table->unsignedBigInteger ( 'country_id' );
            $table->longText ( 'coordinates' )->nullable ();
            $table->string ( 'timezone' )->nullable ();
            $table->tinyInteger ( 'status' )->default ( 1 );
            $table->timestamps ();
            $table->softDeletes ();
            $table->foreign ( 'country_id' )->references ( 'id' )->on ( 'countries' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('country_areas');
    }
};
