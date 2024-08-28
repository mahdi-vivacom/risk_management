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
        Schema::create('top_ups', function (Blueprint $table) {
            $table->id();
            $table->double ( 'amount', 10, 2 )->default ( 0.00 )->nullable ();
            $table->unsignedBigInteger ( 'area_id' )->nullable ();
            $table->tinyInteger ( 'status' )->default ( 1 );
            $table->timestamps();
            $table->softDeletes ();
           
            $table->foreign ( 'area_id' )->references ( 'id' )->on ( 'country_areas' )->onDelete ( 'set null' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_ups');
    }
};
