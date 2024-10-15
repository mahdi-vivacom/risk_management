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
        Schema::create('likelihood_impact_vulnerabilities', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Likelihood', 'Impact', 'Vulnerability'])->nullable();
            $table->string('name')->nullable();
            $table->integer('score')->nullable();
            $table->text('definition')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likelihood_impact_vulnerabilities');
    }
};
