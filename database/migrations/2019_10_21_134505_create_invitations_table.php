<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->unsignedInteger('position_id');
            $table->foreign('position_id')->references('id')->on('positions');
            $table->unsignedInteger('department_id');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->unsignedInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->dateTime('expired_at');
            $table->boolean('is_accepted')->default(false);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('invite_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitations');
    }
}
