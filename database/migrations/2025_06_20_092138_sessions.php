<?php
// Migration for creating the 'sessions' table in Laravel
use Illuminate\Database\Migrations\Migration; // Import Migration class
use Illuminate\Database\Schema\Blueprint; // Import Blueprint class
use Illuminate\Support\Facades\Schema; // Import Schema facade

return new class extends Migration // Return an anonymous migration class
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() // Method to run when migrating
    {
        Schema::create('sessions', function (Blueprint $table) { // Create 'sessions' table
            $table->string('id')->primary(); // Primary key, session id
            $table->foreignId('user_id')->nullable()->index(); // Optional user id (indexed)
            $table->string('ip_address', 45)->nullable(); // Optional IP address (max 45 chars for IPv6)
            $table->text('user_agent')->nullable(); // Optional user agent
            $table->longText('payload'); // Session data payload
            $table->integer('last_activity')->index(); // Last activity timestamp (indexed)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() // Method to run when rolling back
    {
        Schema::dropIfExists('sessions'); // Drop the 'sessions' table if it exists
    }
};
