<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->decimal('payment_amount', 15, 0);
            $table->unsignedBigInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->enum('payment_method', ['Tiền mặt', 'Chuyển khoản']);
            $table->unsignedBigInteger('cashbook_id');
            $table->foreign('cashbook_id')->references('id')->on('cashbooks');
            $table->enum('status', ['Đã xác nhận', 'Chưa xác nhận']);
            $table->text('note')->nullable();
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
        Schema::dropIfExists('bills');
    }
}
