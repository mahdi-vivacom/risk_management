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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string ( 'name' )->unique ();
            $table->string ( 'locale' )->unique ();
            $table->tinyInteger ( 'default' )->default ( 1 )->comment ( '1: Yes, 0: No' );
            $table->tinyInteger ( 'status' )->default ( 1 )->comment ( '1: Active, 0: Deactive' );
            $table->timestamps();
            $table->softDeletes ();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
