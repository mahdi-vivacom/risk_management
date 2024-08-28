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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id ();
            $table->unsignedBigInteger ( 'booking_id' )->nullable ();
            $table->unsignedBigInteger ( 'professional_id' )->nullable ();
            $table->unsignedBigInteger ( 'client_id' )->nullable ();
            $table->dateTime ( 'date_time' )->nullable ();
            $table->double ( 'company_earning', 10, 2 )->nullable ();
            $table->double ( 'professional_earning', 10, 2 )->nullable ();
            $table->double ( 'total_payout_amount', 10, 2 )->nullable ();
            $table->double ( 'rounded_amount', 8, 2 )->nullable ();
            $table->timestamps ();
            $table->softDeletes ();

            $table->foreign ( 'booking_id' )->references ( 'id' )->on ( 'bookings' )->onDelete ( 'set null' );
            $table->foreign ( 'professional_id' )->references ( 'id' )->on ( 'professionals' )->onDelete ( 'set null' );
            $table->foreign ( 'client_id' )->references ( 'id' )->on ( 'clients' )->onDelete ( 'set null' );

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
