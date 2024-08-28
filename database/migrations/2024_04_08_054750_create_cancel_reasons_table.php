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
        Schema::create('cancel_reasons', function (Blueprint $table) {
            $table->id();
            $table->string ( 'reason' )->nullable ();
            $table->tinyInteger ( 'reason_for' )->default ( 1 )->comment ( '1: Client, 2: Professional' );
            $table->tinyInteger ( 'status' )->default ( 1 );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cancel_reasons');
    }
};
