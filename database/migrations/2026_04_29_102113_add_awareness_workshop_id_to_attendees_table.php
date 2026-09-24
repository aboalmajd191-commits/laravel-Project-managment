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
        Schema::table('attendees', function (Blueprint $table) {
            // Make training_id nullable
            $table->unsignedBigInteger('training_id')->nullable()->change();
            
            // Add awareness_workshop_id
            $table->foreignId('awareness_workshop_id')
                  ->nullable()
                  ->after('training_id')
                  ->constrained('awareness_workshops')
                  ->onDelete('cascade');

            // Make other fields nullable as requested
            $table->string('name')->nullable()->change();
            
            // Fix marital_status truncation issue by changing it to string instead of enum
            $table->string('marital_status')->nullable()->change();
            
            // Add name_en and disability_type if they don't exist (they were in the SQL error but not in the original migration I saw)
            if (!Schema::hasColumn('attendees', 'name_en')) {
                $table->string('name_en')->nullable()->after('name');
            }
            if (!Schema::hasColumn('attendees', 'disability_type')) {
                $table->string('disability_type')->nullable()->after('marital_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendees', function (Blueprint $table) {
            $table->dropForeign(['awareness_workshop_id']);
            $table->dropColumn('awareness_workshop_id');
            
            // Reverting changes (Note: revert to original types/mandatory if needed)
            $table->unsignedBigInteger('training_id')->nullable(false)->change();
            $table->string('name')->nullable(false)->change();
            $table->enum('marital_status', ['single', 'married', 'widowed', 'divorced'])->nullable()->change();
        });
    }
};
