<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webforms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('campaign')->nullable();
            $table->boolean('language');
            $table->string('url');
            $table->string('redirect_url')->nullable();
            $table->json('field');
            $table->integer('width');
            $table->integer('height');
            $table->morphs('ownerable');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webforms');
    }
}
