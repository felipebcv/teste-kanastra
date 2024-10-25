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
        Schema::create('boletos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('governmentId');
            $table->string('email');
            $table->decimal('amount', 15, 2);
            $table->date('dueDate');
            $table->uuid('boletoId')->unique();
            $table->enum('generated', ['Y', 'N'])->default('N');
            $table->enum('sendMail', ['Y', 'N'])->default('N');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boletos');
    }
};