<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('name');
            $table->string('model')->nullable();
            $table->string('barcode')->nullable();
            $table->float('price', 8, 2)->nullable();
            $table->float('sale_price', 8, 2);
            $table->string('unit')->default('pc');
            $table->text('description')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('photo_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
