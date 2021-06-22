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
            $table->id();
            $table->integer('category_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('root')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('buying_price',18,2)->unsigned()->default(0)->nullable();
            $table->decimal('selling_price',18,2)->unsigned()->default(0)->nullable();
            $table->dateTime('buying_date')->nullable();
            $table->string('image')->nullable();
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
