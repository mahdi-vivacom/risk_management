<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::create ( 'users', function (Blueprint $table) {
            $table->id ();
            $table->string ( 'name' )->nullable ();
            $table->string ( 'email' )->unique ();
            $table->timestamp ( 'email_verified_at' )->nullable ();
            $table->string ( 'password' );
            $table->string ( 'profile_image' )->nullable ();
            $table->rememberToken ();
            $table->timestamps ();
            $table->softDeletes ();
            $table->unsignedBigInteger ( 'password_set_by_user_id' )->nullable ();
            $table->foreign ( 'password_set_by_user_id' )->references ( 'id' )->on ( 'users' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists ( 'users' );
    }
}
