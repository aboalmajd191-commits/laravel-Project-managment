<?php

// database/migrations/2024_01_01_000006_create_economic_projects_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('economic_projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_name')->comment('اسم المشروع الريادي');
            $table->string('coordinator_name')->comment('اسم المنسقة');
            $table->date('project_date')->comment('تاريخ المشروع');
            $table->string('funder')->comment('الجهة الممولة');
            $table->decimal('total_grant_value', 10, 2)->comment('قيمة المنحة الكلية');
            $table->string('owner_name')->comment('اسم صاحب المشروع');
            $table->decimal('grant_value', 10, 2)->comment('قيمة المنحة الفردية');
            $table->date('grant_date')->comment('تاريخ المنحة');
            $table->unsignedInteger('individuals_count')->comment('عدد الأفراد');
            $table->string('id_number', 20)->comment('رقم الهوية');
            $table->enum('marital_status', ['single', 'married', 'widowed', 'divorced'])
                  ->comment('الحالة الاجتماعية');
            $table->string('education_level')->nullable()->comment('المؤهل العلمي');
            $table->string('governorate', 100)->comment('المحافظة');
            $table->foreignId('submitted_by')
                  ->constrained('users')
                  ->comment('من أدخل البيانات');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->comment('حالة الموافقة');
            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->comment('من وافق على المشروع');
            $table->timestamp('approved_at')->nullable()->comment('وقت الموافقة');
            $table->text('rejection_reason')->nullable()->comment('سبب الرفض');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('economic_projects');
    }
};
