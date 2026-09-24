<?php

// database/migrations/2024_01_01_000004_create_trainings_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم التدريب');
            $table->unsignedInteger('beneficiary_count')->comment('عدد المستفيدين');
            $table->date('start_date')->comment('تاريخ البداية');
            $table->date('end_date')->comment('تاريخ النهاية');
            $table->string('funder')->comment('الجهة الممولة');
            $table->enum('gender_type', ['male', 'female', 'both'])
                  ->default('both')
                  ->comment('تصنيف الجنس');
            $table->foreignId('submitted_by')
                  ->constrained('users')
                  ->comment('من أدخل البيانات');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->comment('حالة الموافقة');
            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->comment('من وافق على التدريب');
            $table->timestamp('approved_at')->nullable()->comment('وقت الموافقة');
            $table->text('rejection_reason')->nullable()->comment('سبب الرفض');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
