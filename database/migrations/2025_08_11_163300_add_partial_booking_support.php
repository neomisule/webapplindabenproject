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
        Schema::table('ngos', function (Blueprint $table) {
            $table->boolean('allow_partial')->nullable()->default(false)->after('program');
            $table->integer('min_hours_per_volunteer')->nullable()->default(2)->after('allow_partial');
        });

        Schema::table('volunteer_bookings', function (Blueprint $table) {
            $table->boolean('is_partial')->nullable()->default(false)->after('status');
        });
    }

    public function down()
    {
        Schema::table('ngos', function (Blueprint $table) {
            $table->dropColumn(['allow_partial', 'min_hours_per_volunteer']);
        });

        Schema::table('volunteer_bookings', function (Blueprint $table) {
            $table->dropColumn(['is_partial']);
        });
    }
};
