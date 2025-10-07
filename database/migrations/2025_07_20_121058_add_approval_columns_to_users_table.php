<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('approval_token', 60)->nullable()->after('remember_token');
            $table->timestamp('approval_token_expires')->nullable()->after('approval_token');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['approval_token', 'approval_token_expires']);
        });
    }
};
