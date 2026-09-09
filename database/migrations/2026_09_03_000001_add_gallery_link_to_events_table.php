<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasColumn('events', 'gallery_link')) {
            Schema::table('events', function (Blueprint $table) {
                $table->string('gallery_link', 500)->nullable()->after('entry_batches');
            });
        }
    }

    public function down(): void {
        if (Schema::hasColumn('events', 'gallery_link')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('gallery_link');
            });
        }
    }
};
