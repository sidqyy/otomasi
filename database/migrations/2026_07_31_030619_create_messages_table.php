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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();
            $table->string('message_id')->unique()->nullable();
            $table->enum('type', ['text', 'image', 'video', 'document', 'audio', 'location', 'contact'])->default('text');
            $table->text('content')->nullable();
            $table->string('media_url')->nullable();
            $table->enum('direction', ['inbound', 'outbound']);
            $table->enum('status', ['pending', 'sent', 'delivered', 'read', 'failed'])->default('pending');
            $table->enum('replied_by', ['bot', 'admin', 'system'])->default('bot');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
