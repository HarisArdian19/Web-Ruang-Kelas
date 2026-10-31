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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. GKU3-01-01
            $table->string('building')->default('GKU 3');
            $table->integer('floor'); // 1 to 10
            $table->integer('room_number'); // 1 to 20
            $table->integer('capacity')->default(45);
            $table->enum('status', ['active', 'maintenance'])->default('active');
            $table->string('maintenance_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->string('day'); // Senin, Selasa, Rabu, Kamis, Jumat, Sabtu
            $table->string('time_slot'); // 06:30, 07:30, ..., 18:30
            $table->string('course_code'); // e.g. BBK1AAB4
            $table->string('course_name'); // e.g. ALGORITMA DAN PEMROGRAMAN
            $table->string('lecturer_code'); // e.g. CSA
            $table->string('lecturer_name'); // e.g. Haris Ardian
            $table->string('class_group'); // e.g. BS1SI-50-REG-11
            $table->enum('status', ['confirmed', 'pending_reschedule'])->default('confirmed');
            $table->timestamps();

            $table->unique(['room_id', 'day', 'time_slot']);
        });

        Schema::create('reschedule_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->nullOnDelete();
            $table->string('lecturer_name');
            $table->string('lecturer_code');
            $table->string('course_name');
            $table->string('course_code');
            $table->string('class_group');
            $table->string('from_day');
            $table->string('from_time_slot');
            $table->foreignId('from_room_id')->constrained('rooms');
            $table->string('target_day');
            $table->string('target_time_slot');
            $table->foreignId('target_room_id')->constrained('rooms');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected', 'rerouted'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reschedule_requests');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('rooms');
    }
};
