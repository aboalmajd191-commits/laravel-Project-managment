<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Individual Support Sessions (Main Entry)
        Schema::create('individual_support_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('individual_support_sessions')->onDelete('cascade');
            
            // Manager Fields
            $table->string('project_name')->nullable();
            $table->string('funder')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            // Data Entry Fields (Beneficiary/Case Info)
            $table->string('specialist')->nullable();
            $table->string('case_code')->nullable();
            $table->integer('age')->nullable();
            $table->string('mobile')->nullable();
            $table->string('address')->nullable();
            $table->string('education_level')->nullable();
            $table->string('marital_status')->nullable();
            $table->text('main_complaint')->nullable();
            
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

        // Individual Session Details (Multiple sessions per entry)
        Schema::create('individual_support_session_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_entry_id')->constrained('individual_support_sessions')->onDelete('cascade');
            $table->string('session_number')->nullable();
            $table->date('session_date')->nullable();
            $table->string('session_time')->nullable();
            $table->text('intervention_file')->nullable();
            $table->text('intervention_summary')->nullable();
            $table->timestamps();
        });

        // Group Support Sessions
        Schema::create('group_support_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('group_support_sessions')->onDelete('cascade');
            
            // Manager Fields
            $table->string('project_name')->nullable();
            $table->string('funder')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
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

        // Group Session Attendees
        Schema::create('group_support_session_attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_session_id')->constrained('group_support_sessions')->onDelete('cascade');
            $table->string('beneficiary_name')->nullable();
            $table->string('id_number')->nullable();
            $table->string('mobile')->nullable();
            $table->string('gender')->nullable();
            $table->integer('age')->nullable();
            $table->string('governorate')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('health_status')->nullable();
            $table->integer('displacement_count')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('individual_support_session_details');
        Schema::dropIfExists('individual_support_sessions');
        Schema::dropIfExists('group_support_session_attendees');
        Schema::dropIfExists('group_support_sessions');
    }
};
