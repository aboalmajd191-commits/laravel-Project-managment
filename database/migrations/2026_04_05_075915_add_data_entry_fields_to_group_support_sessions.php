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
        Schema::table('group_support_sessions', function (Blueprint $table) {
            // Manager Fields
            $table->integer('target_count')->nullable()->after('end_date');
            
            // Data Entry Fields
            $table->string('session_name')->nullable()->after('target_count');
            $table->string('location')->nullable()->after('session_name');
            $table->string('duration')->nullable()->after('location');
            $table->string('session_number')->nullable()->after('duration');
            $table->integer('attendees_count')->nullable()->after('session_number');
            $table->string('session_leader')->nullable()->after('attendees_count');
            $table->string('hosting_entity')->nullable()->after('session_leader');
            $table->string('session_facilitator')->nullable()->after('hosting_entity');
        });
    }

    public function down(): void
    {
        Schema::table('group_support_sessions', function (Blueprint $table) {
            $table->dropColumn(['target_count', 'session_name', 'location', 'duration', 'session_number', 'attendees_count', 'session_leader', 'hosting_entity', 'session_facilitator']);
        });
    }
};
