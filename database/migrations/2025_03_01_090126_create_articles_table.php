<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->text('url')->nullable();
            $table->string('category')->nullable();
            $table->string('source')->nullable();
            $table->string('author')->nullable();
            $table->timestamp('published_at');
            $table->timestamps();
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->index(['published_at', 'source', 'author', 'category']);
            $table->fullText('title');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
