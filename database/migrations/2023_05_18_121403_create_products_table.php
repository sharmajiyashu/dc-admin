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
            $table->string('name',50);
            $table->bigInteger('stock');
            $table->bigInteger('category_id');
            $table->float('mrp');
            $table->float('sp');
            $table->bigInteger('order_limit');
            $table->bigInteger('quantity');
            $table->string('unit')->nullable();
            $table->bigInteger('packing_quantity');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->longtext('detail')->nullable();
            $table->longtext('images')->nullable();
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
