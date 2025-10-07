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
            $table->string('status')->default('checked_in')->after('notes');
        });
    }

    public function down()
    {
        Schema::table('volunteer_checkins', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
