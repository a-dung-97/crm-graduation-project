<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name')->nullable();
            $table->string('last_name');
            $table->string('honorific')->nullable();
            $table->date('birdthday')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('facebook')->nullable();
            $table->unsignedInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')->on('catalogs');
            $table->unsignedInteger('source_id')->nullable();
            $table->foreign('source_id')->references('id')->on('catalogs');



            //company
            $table->string('company')->nullable();
            $table->string('website')->nullable();
            $table->string('tax_code')->nullable();
            $table->string('fax')->nullable();
            $table->integer('number_of_workers')->nullable();
            $table->decimal('revenue')->nullable();
            $table->unsignedInteger('branch_id')->nullable();
            $table->foreign('branch_id')->references('id')->on('catalogs');
            $table->string('office_address')->nullable();

            $table->text('description')->nullable();
            $table->integer('score')->default(0);
            // $table->morphs('ownerable');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('company_id');
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
        Schema::dropIfExists('leads');
    }
}
