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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string ( 'title' )->nullable ();
            $table->tinyInteger ( 'validity' )->default ( 0 )->comment ( '1: Yes, 0: No' );
            $table->tinyInteger ( 'mandatory' )->default ( 0 )->comment ( '1: Yes, 0: No' );
            $table->tinyInteger ( 'application' )->default ( 2 )->comment ( '1: Client, 2: Professional' );
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
        Schema::dropIfExists('documents');
    }
};
