<?php

// database/migrations/2024_01_01_000007_create_economic_project_images_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('economic_project_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('economic_project_id')
                  ->constrained('economic_projects')
                  ->onDelete('cascade')
                  ->comment('المشروع الاقتصادي');
            $table->string('path')->comment('مسار الملف على القرص الخاص');
            $table->string('original_name')->comment('الاسم الأصلي للملف');
            $table->foreignId('uploaded_by')
                  ->constrained('users')
                  ->comment('من رفع الصورة');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('economic_project_images');
    }
};
