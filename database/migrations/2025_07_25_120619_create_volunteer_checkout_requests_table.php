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
        Schema::create('volunteer_checkout_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checkin_id')->constrained('volunteer_checkins');
            $table->foreignId('volunteer_id')->constrained('users');
            $table->integer('lunch_duration')->default(0);
            $table->timestamp('checkout_time')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_checkout_requests');
    }
};
