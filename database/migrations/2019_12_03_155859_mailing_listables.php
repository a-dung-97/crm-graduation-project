<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MailingListables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailing_listables', function (Blueprint $table) {
            $table->unsignedBigInteger('mailing_list_id');
            $table->foreign('mailing_list_id')->references('id')->on('mailing_lists');
            $table->morphs('listable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mailing_listables');
    }
}
