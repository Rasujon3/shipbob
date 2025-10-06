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
        Schema::create('gift_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('value', 50)->nullable();
            $table->string('unit', 50)->nullable();
            $table->string('frozen_amounts')->nullable();
            $table->boolean('is_active')->default(false);
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
        Schema::dropIfExists('gift_boxes');
    }
};
