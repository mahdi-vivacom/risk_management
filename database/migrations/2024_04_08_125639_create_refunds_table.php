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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->string ( 'refund_no' )->nullable ();
            $table->unsignedBigInteger ( 'professional_id' )->nullable ();
            $table->unsignedBigInteger ( 'booking_id' )->nullable ();
            $table->unsignedBigInteger ( 'client_id' )->nullable ();
            $table->double ( 'amount' )->nullable ();
            $table->double ( 'charge' )->nullable ();
            $table->string ( 'details' )->nullable ();
            $table->tinyInteger ( 'status' )->default ( 0 );
            $table->timestamps ();

            $table->foreign ( 'professional_id' )->references ( 'id' )->on ( 'professionals' )->onDelete ( 'set null' );
            $table->foreign ( 'booking_id' )->references ( 'id' )->on ( 'bookings' )->onDelete ( 'set null' );
            $table->foreign ( 'client_id' )->references ( 'id' )->on ( 'clients' )->onDelete ( 'set null' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
