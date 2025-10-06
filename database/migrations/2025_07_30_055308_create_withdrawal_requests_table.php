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
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 20)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('amount')->nullable();
            $table->timestamp('requested_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('status_updated_at')->nullable();
            $table->unsignedBigInteger('status_updated_by')->nullable();
            $table->string('transaction_id')->nullable();
            $table->enum('status',['pending','success','failed'])->default('pending')->nullable();
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
        Schema::dropIfExists('withdrawal_requests');
    }
};
