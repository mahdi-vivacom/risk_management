<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up () : void
    {
        Schema::create ( 'menus', function (Blueprint $table) {
            $table->id ();
            $table->string ( 'label' )->nullable ();
            $table->decimal ( 'serial', 10, 2 )->nullable ()->default ( 0.00 );
            $table->string ( 'route' )->nullable ();
            $table->bigInteger ( 'parent_id' )->nullable ();
            $table->bigInteger ( 'permission_id' )->nullable ();
            $table->string ( 'icon' )->nullable ();
            $table->tinyInteger ( 'status' )->default ( 1 );
            $table->bigInteger ( 'created_by' )->nullable ();
            $table->bigInteger ( 'updated_by' )->nullable ();
            $table->bigInteger ( 'deleted_by' )->nullable ();
            $table->timestamps ();
            $table->softDeletes ();
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down () : void
    {
        Schema::dropIfExists ( 'menus' );
    }
};
