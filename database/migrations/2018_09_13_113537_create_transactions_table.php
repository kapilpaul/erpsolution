<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_no');
            $table->string('type');
            $table->date('date');
            $table->string('category');
            $table->string('tmode');
            $table->string('other_transaction_no')->nullable();
            $table->text('description')->nullable();
            $table->float('debit')->default(0);
            $table->float('credit')->default(0);
            $table->bigInteger('receiver_id')->default(0);
            $table->bigInteger('banktransaction_id')->default(0);
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
        Schema::dropIfExists('transactions');
    }
}
