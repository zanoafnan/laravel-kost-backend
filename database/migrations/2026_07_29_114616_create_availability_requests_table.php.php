<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('availability_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('kost_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('credit_used')->default(5);

            $table->timestamps();

            $table->index('user_id');
            $table->index('kost_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('availability_requests');
    }
};