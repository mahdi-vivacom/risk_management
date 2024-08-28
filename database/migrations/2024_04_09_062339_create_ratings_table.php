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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger ( 'booking_id' );
            $table->string ( 'client_rating_points' )->nullable ();
            $table->text ( 'client_comment' )->nullable ();
            $table->string ( 'professional_rating_points' )->nullable ();
            $table->text ( 'professional_comment' )->nullable ();
            $table->timestamps ();

            $table->foreign ( 'booking_id' )->references ( 'id' )->on ( 'bookings' )->onDelete ( 'cascade' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
