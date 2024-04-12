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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            
            // $table->unsignedBigInteger('book_id');
            $table->text('review');
            $table->unsignedTinyInteger('rating');

            $table->timestamps();
            // $table->foreign('book_id')->references('id')->on('books') // create a foreign key for "book_id" field of this table, refers to the primary key (id) on books table. 
            //     ->onDelete('cascade');

            $table->foreignId('book_id')->constrained() // this is an equevelant statment insted of tow lines above. (this used when we deeling with default tables feilds names (for primary key reference on the other table) ).
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
