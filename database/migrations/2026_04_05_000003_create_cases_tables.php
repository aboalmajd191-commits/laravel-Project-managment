<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Judicial Representations
        Schema::create('judicial_representations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('judicial_representations')->onDelete('cascade');
            
            // Manager Fields
            $table->string('project_name')->nullable();
            $table->string('funding_agency')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            // Data Entry Fields
            $table->string('beneficiary_name')->nullable();
            $table->string('id_number')->nullable();
            $table->string('region')->nullable();
            $table->string('mobile')->nullable();
            $table->boolean('has_disability')->default(false);
            $table->string('marital_status')->nullable();
            $table->integer('individuals_count')->nullable();
            $table->string('health_status')->nullable();
            $table->integer('cases_count')->nullable();
            $table->string('case_type')->nullable(); // شرعي , نظامي
            $table->string('case_number')->nullable();
            $table->string('case_name_type')->nullable();
            $table->string('court_name')->nullable();
            $table->date('lawsuit_date')->nullable();
            $table->string('case_status')->nullable(); // مفتوحة , مغلقة
            $table->date('closing_date')->nullable();
            $table->decimal('total_paid', 10, 2)->nullable();
            
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

        // Legal Representations
        Schema::create('legal_representations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('legal_representations')->onDelete('cascade');
            
            // Manager Fields
            $table->string('project_name')->nullable();
            $table->string('funding_agency')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            // Data Entry Fields
            $table->string('beneficiary_name')->nullable();
            $table->string('id_number')->nullable();
            $table->string('region')->nullable();
            $table->string('mobile')->nullable();
            $table->boolean('has_disability')->default(false);
            $table->string('marital_status')->nullable();
            $table->integer('individuals_count')->nullable();
            $table->string('health_status')->nullable();
            $table->integer('cases_count')->nullable();
            $table->string('extract_type')->nullable(); // شرعي , نظامي
            $table->string('extract_type_detail')->nullable();
            $table->date('extraction_date')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            
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

        // Mediations
        Schema::create('mediations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('mediations')->onDelete('cascade');
            
            // Manager Fields
            $table->string('project_name')->nullable();
            $table->string('funding_agency')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            // Data Entry Fields
            $table->string('beneficiary_name')->nullable();
            $table->string('id_number')->nullable();
            $table->string('region')->nullable();
            $table->string('mobile')->nullable();
            $table->boolean('has_disability')->default(false);
            $table->string('marital_status')->nullable();
            $table->integer('individuals_count')->nullable();
            $table->string('health_status')->nullable();
            $table->integer('cases_count')->nullable();
            $table->string('mediation_type')->nullable(); // شرعي , نظامي
            $table->date('extraction_date')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            
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
        Schema::dropIfExists('judicial_representations');
        Schema::dropIfExists('legal_representations');
        Schema::dropIfExists('mediations');
    }
};
