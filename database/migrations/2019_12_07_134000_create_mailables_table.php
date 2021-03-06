<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('email_id');
            $table->foreign('email_id')->references('id')->on('emails');
            $table->morphs('mailable');
            $table->boolean('clicked')->default(false);
            $table->boolean('opened')->default(false);
            $table->boolean('delivered')->default(false);
            $table->boolean('failed')->default(false);
            $table->boolean('unsubscribed')->default(false);
            $table->boolean('complained')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mailables');
    }
}
