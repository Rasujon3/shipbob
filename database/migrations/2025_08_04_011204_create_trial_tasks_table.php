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
        Schema::create('trial_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('num_of_task')->nullable();
            $table->string('commission')->nullable();
            $table->string('time_duration')->nullable();
            $table->string('time_unit')->nullable();
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
        Schema::dropIfExists('trial_tasks');
    }
};
