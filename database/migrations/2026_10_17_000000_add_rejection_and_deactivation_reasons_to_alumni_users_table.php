<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('alumni_users', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('status');
            $table->text('deactivation_reason')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('alumni_users', function (Blueprint $table) {
            $table->dropColumn(['rejection_reason', 'deactivation_reason']);
        });
    }
};
