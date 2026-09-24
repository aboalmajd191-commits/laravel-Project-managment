<?php

// database/migrations/2024_01_01_000002_create_projects_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('manager_id')
                  ->constrained('users')
                  ->comment('مدير المشروع');
            $table->enum('service_type', [
                'training',
                'awareness_workshop',
                'economic_empowerment',
                'cash_assistance',
                'in_kind_assistance',
                'community_initiatives',
                'support_sponsorship',
            ])->comment('نوع الخدمة');
            $table->enum('status', ['active', 'completed', 'cancelled'])
                  ->default('active')
                  ->comment('حالة المشروع');
            $table->date('start_date')->nullable()->comment('تاريخ البدء');
            $table->date('end_date')->nullable()->comment('تاريخ الانتهاء');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
