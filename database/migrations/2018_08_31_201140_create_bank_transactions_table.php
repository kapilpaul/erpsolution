<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banktransactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->float('debit')->default(0);
            $table->float('credit')->default(0);
            $table->string('slip_id')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('bank_id');
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
        Schema::dropIfExists('banktransactions');
    }
}
