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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livro_id')->constrained()->onDelete('cascade'); // Chave estrangeira para livros
            $table->foreignId('user_id')->constrained()->onDelete('cascade');   // Chave estrangeira para users (o usuario que fez a reserva)
            $table->dateTime('data_reserva');
            $table->dateTime('data_expiracao')->nullable(); // Data limite para pegar o livro
            $table->string('status')->default('pendente'); // Ex: 'pendente', 'ativa', 'cancelada', 'concluida'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};