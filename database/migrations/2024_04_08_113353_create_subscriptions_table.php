<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::create ( 'subscriptions', function (Blueprint $table) {
            $table->id ();
            $table->string ( 'title' )->nullable ();
            $table->string ( 'label' )->nullable ();
            $table->unsignedBigInteger ( 'area_id' )->nullable ();
            $table->double ( 'amount' )->nullable ();
            $table->string ( 'renewal' )->nullable ();
            $table->text ( 'details' )->nullable ();
            $table->text ( 'description' )->nullable ();
            $table->tinyInteger ( 'status' )->default ( 0 );
            $table->timestamps ();
            $table->softDeletes ();
            $table->foreign ( 'area_id' )->references ( 'id' )->on ( 'country_areas' )->onDelete ( 'set null' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists ( 'subscriptions' );
    }
}
