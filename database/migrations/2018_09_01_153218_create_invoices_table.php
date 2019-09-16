<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('invoice_no');
            $table->text('details')->nullable();
            $table->float('total_amount')->default(0);
            $table->float('total_tax')->default(0);
            $table->float('total_discount')->default(0);
            $table->float('grand_total')->default(0);
            $table->bigInteger('customer_id');
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
        Schema::dropIfExists('invoices');
    }
}
