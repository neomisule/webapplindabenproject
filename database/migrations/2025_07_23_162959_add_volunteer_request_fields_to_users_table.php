<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
        $table->timestamp('volunteer_requested_at')->nullable();
        $table->timestamp('volunteer_approved_at')->nullable();
        $table->string('volunteer_approval_token')->nullable();
        $table->timestamp('volunteer_token_expires')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'volunteer_requested_at',
            'volunteer_approved_at',
            'volunteer_approval_token',
            'volunteer_token_expires'
        ]);
    });
    }
};
