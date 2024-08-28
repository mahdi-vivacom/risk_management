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
        Schema::create('professional_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger ( 'professional_id' )->nullable ();
            $table->unsignedBigInteger ( 'skill_id' )->nullable ();
            $table->timestamps();
            $table->foreign ( 'professional_id' )->references ( 'id' )->on ( 'professionals' )->onDelete ( 'set null' );
            $table->foreign ( 'skill_id' )->references ( 'id' )->on ( 'skills' )->onDelete ( 'set null' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professional_skills');
    }
};
