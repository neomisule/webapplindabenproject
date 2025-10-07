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
        // Create the pivot table for task_ngo relationship
        Schema::create('ngo_task', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('ngo_id');
            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('ngo_id')->references('id')->on('ngos')->onDelete('cascade');

            // Add unique constraint to prevent duplicate entries
            $table->unique(['task_id', 'ngo_id']);
        });

        Schema::table('task_assignments', function (Blueprint $table) {
            $table->enum('assignment_type', ['manual', 'ngo'])->default('manual')->after('user_id');
            $table->unsignedBigInteger('source_id')->nullable()->after('assignment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ngo_task');

        Schema::table('task_assignments', function (Blueprint $table) {
            $table->dropColumn(['assignment_type', 'source_id']);
        });
    }
};
