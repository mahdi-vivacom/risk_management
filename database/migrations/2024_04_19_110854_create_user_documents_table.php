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
        Schema::create('user_documents', function (Blueprint $table) {
            $table->id();
            $table->string ( 'file' );
            $table->unsignedBigInteger ( 'client_id' )->nullable ();
            $table->unsignedBigInteger ( 'professional_id' )->nullable ();
            $table->dateTime ( 'validity' )->nullable ();
            $table->tinyInteger ( 'status' )->default ( 1 )->comment ( '1: Active, 0: Deactive' );
            $table->timestamps ();
            $table->softDeletes ();

            $table->foreign ( 'client_id' )->references ( 'id' )->on ( 'clients' )->onDelete ( 'set null' );
            $table->foreign ( 'professional_id' )->references ( 'id' )->on ( 'professionals' )->onDelete ( 'set null' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_documents');
    }
};
