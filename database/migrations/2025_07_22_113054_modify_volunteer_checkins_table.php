<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('volunteer_checkins', function (Blueprint $table) {
            $table->dropColumn(['lunch_start', 'lunch_end']);

            $table->integer('lunch_duration')
                  ->default(0)
                  ->after('checkout_time')
                  ->comment('Lunch duration in minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('volunteer_checkins', function (Blueprint $table) {
            $table->time('lunch_start')->nullable()->after('checkout_time');
            $table->time('lunch_end')->nullable()->after('lunch_start');

            $table->dropColumn('lunch_duration');
        });
    }
};
