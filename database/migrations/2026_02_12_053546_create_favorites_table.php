<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user')->index(); // Username from session
            $table->string('imdbID'); // Movie IMDB ID
            $table->string('title');
            $table->string('year');
            $table->text('poster')->nullable();
            $table->timestamps();

            // Prevent duplicate favorites
            $table->unique(['user', 'imdbID']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorites');
    }
}
