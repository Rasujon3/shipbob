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
            $table->string('unit',20)->default('X')->nullable()->after('task_will_block');
            $table->string('value',20)->default(2)->nullable()->after('task_will_block');
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
