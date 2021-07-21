<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->string('customer_name')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('sub_total',18,2)->unsigned()->default(0)->nullable();
            $table->decimal('vat',18,2)->unsigned()->default(0)->nullable();
            $table->decimal('vat_value',18,2)->unsigned()->default(0)->nullable();
            $table->decimal('total',18,2)->unsigned()->default(0)->nullable();
            $table->string('pay')->nullable();
            $table->string('due')->nullable();
            $table->string('pay_by')->nullable();
            $table->string('order_date')->nullable();
            $table->string('order_month')->nullable();
            $table->string('order_year')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
