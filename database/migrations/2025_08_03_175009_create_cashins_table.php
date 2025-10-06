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
        Schema::create('cashins', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('package_id')->nullable();
            $table->string('amount');
            $table->string('bouns_amount')->nullable();
            $table->date('date');
            $table->string('time');
            $table->string('transaction_hash')->nullable();
            $table->string('status')->default('Pending')->nullable();
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
        Schema::dropIfExists('cashins');
    }
};
