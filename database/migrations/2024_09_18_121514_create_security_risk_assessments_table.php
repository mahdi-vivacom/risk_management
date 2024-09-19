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
        Schema::create('security_risk_assessments', function (Blueprint $table) {
            $table->id();
            $table->integer('risk_score')->nullable();
            $table->text('mitigation_measures')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();

            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign(columns: 'area_id')->references('id')->on('country_areas')->onDelete('set null');
            $table->foreign(columns: 'client_id')->references('id')->on('clients')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_risk_assessments');
    }
};
