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
        Schema::create('volunteer_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ngo_id')->constrained('ngos')->onDelete('cascade');
            $table->string('booking_code')->unique();
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->time('lunch_time')->nullable();
            $table->enum('status', ['booked', 'checked_in', 'checked_out', 'cancelled'])->default('booked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_bookings');
    }
};
