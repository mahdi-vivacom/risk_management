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
        Schema::create('sos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger ( 'country_id' );
            $table->string ( 'number' )->nullable ();
            $table->tinyInteger ( 'status' )->default ( 1 );
            $table->tinyInteger ( 'application' )->nullable ();
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
        Schema::dropIfExists('sos');
    }
};
