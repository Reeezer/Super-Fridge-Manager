<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveBooksAuthor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn('author_id');
        });

        Schema::dropIfExists('authors');

        Schema::dropIfExists('books');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // redo /database/migrations/2021_09_19_231431_create_books_table.php
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->integer('pages')->unsigned();
            $table->integer('quantity')->unsigned();

            $table->timestamps();
        });

        // redo /database/migrations/2021_09_22_084721_create_authors_table.php
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // redo database/migrations/2021_09_22_090440_add_author_fk_to_books.php
        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('author_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
}
