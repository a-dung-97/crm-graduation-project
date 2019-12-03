<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpportunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->date('end_date');
            $table->unsignedBigInteger('type_id')->nullable();;
            $table->foreign('type_id')->references('id')->on('catalogs');
            $table->unsignedBigInteger('status_id')->nullable();;
            $table->foreign('status_id')->references('id')->on('catalogs');
            $table->unsignedBigInteger('source_id')->nullable();;
            $table->foreign('source_id')->references('id')->on('catalogs');
            $table->string('next_step')->nullable();
            $table->decimal('probability_of_success', 3, 2);
            $table->decimal('value', 15, 0)->nullable();
            $table->decimal('expected_sales', 15, 0)->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->morphs('ownerable');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
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
        Schema::dropIfExists('opportunities');
    }
}
