<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('awareness_workshops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('awareness_workshops')->onDelete('cascade');
            
            // Manager Fields
            $table->string('project_name')->nullable();
            $table->string('funder')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('total_beneficiaries')->nullable();
            
            // Data Entry Fields
            $table->string('meeting_name')->nullable();
            $table->string('meeting_location')->nullable();
            $table->string('meeting_duration')->nullable();
            $table->string('session_number')->nullable();
            $table->integer('attendance_count')->nullable();
            $table->string('meeting_moderator')->nullable();
            $table->string('hosting_party')->nullable();
            $table->string('session_facilitator')->nullable();
            
            // Tracking fields
            $table->foreignId('submitted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('status', ['draft', 'active', 'completed'])->default('draft');
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('awareness_workshops');
    }
};
