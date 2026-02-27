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
        Schema::create('loans_equipaments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_requester')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('id_secretary')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('id_equipament')->nullable()->constrained('equipaments')->nullOnDelete();
            $table->string('justification', 1000);
            $table->dateTime('authorization_date')->nullable();
            $table->enum('status', [1, 2, 3])->default(1);
            $table->dateTime('withdrawal_date')->nullable();
            $table->dateTime('return_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans_equipaments');
    }
};
