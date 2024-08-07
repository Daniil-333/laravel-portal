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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // имя
            $table->string('slug')->unique(); // slug для роута
            $table->string('short_desc'); // короткое описание
            $table->text('description')->nullable(); // описание
            $table->unsignedBigInteger('file_id')->unsigned()->nullable(); // ID файла
            $table->unsignedBigInteger('category_id')->unsigned(); // ID категории
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
