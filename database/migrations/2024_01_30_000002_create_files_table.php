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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('path'); // путь
            $table->string('extension', 5); // расширение
            $table->unsignedBigInteger('receipt_id')->unsigned(); // ID  рецепта
            $table->timestamps();
            $table->foreign('receipt_id')
                ->references('id')
                ->on('receipts')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
