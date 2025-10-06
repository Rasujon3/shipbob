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
        Schema::table('gifts', function (Blueprint $table) {
            $table->string('task_will_block',20)->nullable()->after('user_id');
            $table->string('frozen_amount_task_will_block',20)->nullable()->after('frozen_amounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gifts', function (Blueprint $table) {
            $table->dropColumn('task_will_block');
            $table->dropColumn('frozen_amount_task_will_block');
        });
    }
};
