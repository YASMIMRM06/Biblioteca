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
        Schema::create('emprestimos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livro_id')->constrained()->onDelete('cascade'); // Chave estrangeira para livros
            $table->foreignId('user_id')->constrained()->onDelete('cascade');   // Chave estrangeira para users (o usuario que fez o emprestimo)
            $table->date('data_emprestimo');
            $table->date('data_devolucao_prevista'); // Adicionei para melhor controle
            $table->date('data_devolucao_real')->nullable(); // Quando o livro realmente foi devolvido
            $table->string('status')->default('emprestado'); // Ex: 'emprestado', 'devolvido', 'atrasado'
            $table->decimal('multa', 8, 2)->default(0.00); // Para multas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emprestimos');
    }
};