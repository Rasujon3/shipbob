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
        Schema::create('r_t_t_assign_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('rtt_task_id')->nullable()->constrained('r_t_t_tasks')->onDelete('cascade');
            $table->string('num_of_tasks',20)->default(0)->nullable();
            $table->enum('status',['Completed', 'Incomplete'])->default('Incomplete')->nullable();
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
        Schema::dropIfExists('r_t_t_assign_tasks');
    }
};
