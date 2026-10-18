<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE event_participants MODIFY COLUMN status ENUM('confirmed','pending','cancelled','declined') DEFAULT 'pending'");
        }
    }

    public function down(): void {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE event_participants MODIFY COLUMN status ENUM('confirmed','pending','cancelled') DEFAULT 'pending'");
        }
    }
};
