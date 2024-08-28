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
        Schema::create('navigations', function (Blueprint $table) {
            $table->id();
            $table->string ( 'name' )->nullable ();
            $table->string ( 'slug' )->nullable ();
            $table->string ( 'image' )->nullable ();
            $table->integer ( 'sequence' )->default ( 1 );
            $table->tinyInteger ( 'type' )->default ( 1 )->comment ( '1: Client, 2: Professional' );
            $table->tinyInteger ( 'status' )->default ( 1 )->comment ( '1: Active, 0: Deactive' );
            $table->timestamps ();
            $table->softDeletes ();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navigations');
    }
};
