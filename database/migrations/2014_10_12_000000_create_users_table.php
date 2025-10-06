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
            $table->string('uid', 20)->nullable();
            $table->string('name')->nullable();
            $table->string('role')->default('user')->nullable();
            $table->string('username')->nullable();
            $table->string('phone')->nullable();
            $table->string('withdraw_acc_number')->nullable();
            $table->string('password')->nullable();
            $table->string('withdraw_password')->nullable();
            $table->string('balance')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->nullable()->default('Active');
            $table->rememberToken();
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
