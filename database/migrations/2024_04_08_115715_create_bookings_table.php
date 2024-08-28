<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->unsigned()->nullable();
            $table->unsignedBigInteger('professional_id')->unsigned()->nullable();
            $table->unsignedBigInteger('country_area_id')->unsigned()->nullable();
            $table->unsignedBigInteger('skill_id')->unsigned()->nullable();
            $table->unsignedBigInteger('cancel_reason_id')->unsigned()->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('location')->nullable();
            $table->string('company_cut')->nullable();
            $table->string('unique_id')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('complete')->default(0)->comment('1:Yes, 0:No');
            $table->dateTime('end_at')->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $table->foreign('professional_id')->references('id')->on('professionals')->onDelete('set null');
            $table->foreign('country_area_id')->references('id')->on('country_areas')->onDelete('set null');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('set null');
            $table->foreign('cancel_reason_id')->references('id')->on('cancel_reasons')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
