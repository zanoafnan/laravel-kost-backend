<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kosts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('owner_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('name');
            $table->text('description');

            $table->string('location');

            $table->decimal('price', 12, 2);

            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('location');
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kosts');
    }
};