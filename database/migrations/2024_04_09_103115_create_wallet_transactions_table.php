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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id ();
            $table->unsignedBigInteger ( 'professional_id' )->nullable ();
            $table->text ( 'narration' )->comment ( '1:Subscription, 2:Refund' );
            $table->integer ( 'transaction_type' );
            $table->string ( 'amount', 191 );
            $table->unsignedBigInteger ( 'subscription_id' )->nullable ();
            $table->unsignedBigInteger ( 'booking_id' )->nullable ();
            $table->text ( 'description' )->nullable ();
            $table->string ( 'receipt_number', 191 )->nullable ();
            $table->string ( 'transaction_id', 191 )->nullable ();
            $table->timestamps ();
            $table->softDeletes ();
            
            $table->foreign ( 'professional_id' )->references ( 'id' )->on ( 'professionals' )->onDelete ( 'set null' );
            $table->foreign ( 'subscription_id' )->references ( 'id' )->on ( 'subscriptions' )->onDelete ( 'set null' );
            $table->foreign ( 'booking_id' )->references ( 'id' )->on ( 'bookings' )->onDelete ( 'set null' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
