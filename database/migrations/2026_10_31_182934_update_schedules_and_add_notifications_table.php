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
        Schema::table('schedules', function (Blueprint $table) {
            $table->text('cancellation_reason')->nullable()->after('status');
            $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');
        });

        Schema::table('reschedule_requests', function (Blueprint $table) {
            $table->string('target_end_slot')->nullable()->after('target_time_slot');
            $table->string('from_end_slot')->nullable()->after('from_time_slot');
        });

        Schema::create('email_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('lecturer_name');
            $table->string('lecturer_email');
            $table->string('type'); // reminder_h1, cancellation, reschedule
            $table->string('subject');
            $table->text('body');
            $table->string('class_info')->nullable();
            $table->string('status')->default('sent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_notifications');

        Schema::table('reschedule_requests', function (Blueprint $table) {
            $table->dropColumn(['target_end_slot', 'from_end_slot']);
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn(['cancellation_reason', 'cancelled_at']);
        });
    }
};
