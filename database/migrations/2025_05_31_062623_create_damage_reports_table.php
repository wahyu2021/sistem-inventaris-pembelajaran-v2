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
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')
                    ->constrained('items')
                    ->onDelete('cascade');
            $table->foreignId('reported_by_user_id')
                    ->constrained('items')
                    ->onDelete('cascade');
            $table->text('description');
            $table->string('status')->default('dilaporkan');
            $table->text('admin_notes')->nullable();
            $table->string('image_damage')->nullable();
            $table->timestamp('reported_at')->useCurrent();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_reports');
    }
};
