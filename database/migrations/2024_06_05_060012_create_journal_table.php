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
    Schema::create('journals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('entry_id')->constrained()->unique()->onDelete('cascade');
        $table->enum('mood', ['happy', 'sad', 'angry', 'anxious', 'proud'])->nullable();
        $table->string('content');
        $table->string('image')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
};
