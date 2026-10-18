<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contact_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email_address');
            $table->string('phone_ext', 10)->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false)->index();
            $table->timestamp('responded_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_inquiries');
    }
};
