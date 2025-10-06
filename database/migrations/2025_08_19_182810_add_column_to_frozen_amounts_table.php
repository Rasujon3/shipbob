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
        Schema::table('frozen_amounts', function (Blueprint $table) {
            $table->enum('status',['Active','Inactive'])->default('Active')->nullable()->after('amount');
            $table->string('task_will_block',20)->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('frozen_amounts', function (Blueprint $table) {
            //
        });
    }
};
