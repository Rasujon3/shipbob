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
        Schema::table('assigned_trial_tasks', function (Blueprint $table) {
            $table->string('num_of_tasks',20)->default(0)->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assigned_trial_tasks', function (Blueprint $table) {
            $table->dropColumn('num_of_tasks');
        });
    }
};
