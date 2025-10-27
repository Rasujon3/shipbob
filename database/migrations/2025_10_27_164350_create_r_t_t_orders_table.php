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
        Schema::create('r_t_t_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('rtt_task_id')->nullable()->constrained('r_t_t_tasks')->onDelete('cascade');
            $table->foreignId('rtt_product_id')->nullable()->constrained('r_t_t_products')->onDelete('cascade');
            $table->string('amount')->nullable();
            $table->string('status',20)->nullable()->default('Incomplete');
            $table->timestamp('completed_at')->nullable();
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
        Schema::dropIfExists('r_t_t_orders');
    }
};
