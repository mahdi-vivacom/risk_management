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
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger ( 'country_id' )->nullable ();
            $table->string ( 'slug' )->nullable ();
            $table->string ( 'title' )->nullable ();
            $table->longText ( 'content' )->nullable ();
            $table->tinyInteger ( 'application' )->nullable ()->comment ( '1: Client, 2: Professional' );
            $table->tinyInteger ( 'status' )->default ( 1 );
            $table->timestamps ();
            $table->softDeletes ();

            $table->foreign ( 'country_id' )->references ( 'id' )->on ( 'countries' )->onDelete ( 'set null' );

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_pages');
    }
};
