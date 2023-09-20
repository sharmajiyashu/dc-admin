<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('product_id');
            $table->float('p_price');
            $table->float('p_mrp');
            $table->integer('quantity');
            $table->float('total');
            $table->string('status')->default(0)->comment('0 => active , 1 => orderd');
            $table->string('order_id')->nullable();
            $table->string('store_code');
            $table->bigInteger('vendor_id')->nullable();
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
        Schema::dropIfExists('carts');
    }
};
