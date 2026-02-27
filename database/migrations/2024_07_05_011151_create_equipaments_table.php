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
        Schema::create('equipaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description', 1000);
            $table->string('manufacturer_number', 100)->default('');
            $table->string('asset_number', 11)->default('');
            $table->string('brand', 100)->default('');
            $table->string('model', 100)->default('');
            $table->string('image')->nullable();
            $table->boolean('loaned')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipaments');
    }
};
