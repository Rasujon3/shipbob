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
        Schema::create('welcome_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount',10, 2)->nullable()->default(0);
            $table->string('num_of_tasks',20)->nullable()->default(0);
            $table->string('completed_tasks',20)->nullable()->default(0);
            $table->string('remaining_tasks',20)->nullable()->default(0);
            $table->enum('status', ['Complete', 'Incomplete'])->default('Incomplete')->nullable();
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
        Schema::dropIfExists('welcome_bonuses');
    }
};
