<?php

// database/migrations/2024_01_01_000005_create_attendees_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')
                  ->constrained('trainings')
                  ->onDelete('cascade')
                  ->comment('التدريب المرتبط');
            $table->string('name')->comment('الاسم الكامل');
            $table->string('id_number', 20)->nullable()->comment('رقم الهوية');
            $table->string('specialty', 255)->nullable()->comment('التخصص');
            $table->unsignedTinyInteger('age')->nullable()->comment('العمر');
            $table->string('phone', 20)->nullable()->comment('رقم الجوال');
            $table->string('governorate', 100)->nullable()->comment('المحافظة');
            $table->enum('marital_status', ['single', 'married', 'widowed', 'divorced'])
                  ->nullable()
                  ->comment('الحالة الاجتماعية');
            $table->boolean('has_disability')->default(false)->comment('ذوي إعاقة');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendees');
    }
};
