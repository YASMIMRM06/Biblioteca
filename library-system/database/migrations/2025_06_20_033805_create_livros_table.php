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
        Schema::create('livros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('editora_id')->constrained('editoras')->onDelete('cascade');
            $table->string('titulo');
            $table->string('isbn')->unique();
            $table->integer('ano_publicacao')->nullable();
            $table->integer('qtd_exemplares')->default(1);
            $table->string('autor')->nullable();
            $table->string('status')->default('disponivel');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livros');
    }
};