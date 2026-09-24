<?php

// database/migrations/2024_01_01_000001_create_users_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'project_manager', 'data_entry'])->comment('دور المستخدم في النظام');
            $table->boolean('is_active')->default(true)->comment('حالة الحساب — false = معطّل مع بقاء البيانات');
            $table->string('phone', 20)->nullable()->comment('رقم الجوال');
            $table->string('id_number', 20)->nullable()->comment('رقم الهوية');
            $table->string('specialty', 255)->nullable()->comment('التخصص');
            $table->string('governorate', 100)->nullable()->comment('المحافظة');
            $table->text('address')->nullable()->comment('العنوان التفصيلي');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
