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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('role_id')->nullable();
            $table->string('status')->default(1);
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('mobile')->nullable();
            $table->enum('gender',['Male','Female','Other'])->default('Other');
            $table->date('dob')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->longText('address')->nullable();
            $table->string('pin')->nullable();
            $table->string('image')->nullable();
            $table->string('store_name')->nullable();
            $table->string('store_code')->nullable();
            $table->string('store_image')->nullable();
            $table->string('active_store_code')->nullable();
            $table->string('otp')->nullable();
            $table->string('otp_verify')->default(0);
            $table->string('is_register')->default(0);
            $table->string('is_notify')->default(1);
            $table->date('order_approve_date')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->longText('remember_token')->nullable();
            // $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
