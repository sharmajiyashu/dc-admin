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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('name')->nullable();
            $table->bigInteger('category_id');
            $table->string('status')->default(1);
            $table->longText('detail')->nullable();
            $table->longText('images')->nullable();
            $table->float('sp')->nullable();
            $table->float('mrp')->nullable();
            $table->integer('stock')->nullable();
            $table->integer('order_limit')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('unit')->nullable();
            $table->integer('packing_quantity')->nullable();
            $table->string('is_limited')->default(1);
            $table->string('is_admin')->default(0);
            $table->softDeletes();
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
};
