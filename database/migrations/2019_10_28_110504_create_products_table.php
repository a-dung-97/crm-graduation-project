<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['product', 'service'])->default('product');
            $table->string('name');
            $table->string('code');
            $table->string('unit')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('brand')->nullable();
            $table->string('barcode')->nullable();
            $table->decimal('perchase_price', 15, 2)->default(0);
            $table->text('perchase_detail')->nullable();
            $table->tinyInteger('tax')->default(0);
            $table->decimal('sale_price', 15, 2)->default(0);
            $table->string('distributor')->nullable();
            $table->text('sale_detail')->nullable();
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
        Schema::dropIfExists('products');
    }
}
