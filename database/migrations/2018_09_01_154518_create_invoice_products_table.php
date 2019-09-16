<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('invoice_products', function (Blueprint $table) {
            $table->bigInteger('invoice_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->float('quantity')->default(0);
            $table->float('price')->default(0);
            $table->float('discount')->default(0);
            $table->float('total')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('invoice_id')
                  ->references('id')->on('invoices')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('invoice_products');
    }
}
