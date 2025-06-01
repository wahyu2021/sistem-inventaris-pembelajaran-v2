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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                    ->constrained('categories')
                    ->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unique_code')->unique()->nullable();
            $table->integer('quantity')->default(0);
            $table->string('condition')->default('baik');
            $table->string('image')->nullable();
            $table->string('location');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
