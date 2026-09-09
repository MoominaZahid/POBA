<?php
// FILE: database/migrations/2024_01_01_000002_create_events_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('registration_required')->default(false);
            $table->string('focal_person_name')->nullable();
            $table->string('focal_person_number')->nullable();
            $table->json('entry_batches')->nullable(); // e.g. [3,9]
            $table->string('gallery_link', 500)->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_upcoming')->default(true);
            $table->timestamps();
        });

        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('alumni_user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['confirmed','pending','cancelled','declined'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('event_participants');
        Schema::dropIfExists('events');
    }
};
