<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Legal Consultations
        Schema::create('legal_consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('legal_consultations')->onDelete('cascade');
            
            // Manager Fields
            $table->string('project_name')->nullable();
            $table->string('funder')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            // Data Entry Fields - Personal
            $table->string('full_name')->nullable();
            $table->string('id_number')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('gender')->nullable();
            $table->string('phone')->nullable();
            $table->string('current_address')->nullable();
            $table->string('previous_address')->nullable();
            $table->integer('displacement_count')->nullable();
            $table->boolean('has_disability')->default(false);
            
            // Data Entry Fields - Case
            $table->string('source')->nullable();
            $table->text('problem_description')->nullable();
            $table->text('legal_aid_details')->nullable();
            
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

        // Psychological Consultations
        Schema::create('psychological_consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('psychological_consultations')->onDelete('cascade');
            
            // Manager Fields
            $table->string('project_name')->nullable();
            $table->string('funding_agency')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            // Data Entry Fields - Personal
            $table->string('full_name')->nullable();
            $table->string('case_code')->nullable();
            $table->string('id_number')->nullable();
            $table->integer('age')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('mobile')->nullable();
            $table->string('education_level')->nullable();
            $table->string('mission')->nullable();
            $table->string('displacement_status')->nullable();
            $table->string('original_governorate')->nullable();
            $table->string('primary_address')->nullable();
            $table->string('displacement_address')->nullable();
            $table->string('health_status')->nullable();
            $table->string('disability_type')->nullable();
            
            // Data Entry Fields - Case
            $table->text('case_description')->nullable();
            $table->text('procedure_guidance')->nullable();
            $table->text('recommendations')->nullable();
            
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
        Schema::dropIfExists('legal_consultations');
        Schema::dropIfExists('psychological_consultations');
    }
};
