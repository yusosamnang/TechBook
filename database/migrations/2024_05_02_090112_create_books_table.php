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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('ISBN')->unique();
            $table->string('title');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('author_name');
            $table->date('published_date');
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('status', ['Approved', 'Denied', 'Pending']);
            $table->enum('type', ['Free', 'Paid']);
            $table->string('cover_url');
            $table->string('book_url');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Added user_id column
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
