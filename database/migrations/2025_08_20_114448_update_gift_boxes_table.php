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
        Schema::table('gift_boxes', function (Blueprint $table) {
            // Remove the two specified columns
            $table->dropColumn(['user_id', 'frozen_amounts']);

            // Add the new 'gift_id' column
            $table->string('gift_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gift_boxes', function (Blueprint $table) {
            // Add the columns back for rollback
            $table->string('user_id')->nullable()->after('id');
            $table->string('frozen_amounts')->nullable()->after('unit');

            // Remove the 'gift_id' column
            $table->dropColumn('gift_id');
        });
    }
};
