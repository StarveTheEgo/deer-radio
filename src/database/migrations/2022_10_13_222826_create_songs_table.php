<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {

    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();

            $table->string('title', 196);

            $table->bigInteger('author_id')->unsigned();
            $table->foreign('author_id')->references('id')->on('authors');

            $table->bigInteger('album_id')->unsigned()->nullable();
            $table->foreign('album_id')->references('id')->on('albums');

            $table->bigInteger('label_id')->unsigned()->nullable();
            $table->foreign('label_id')->references('id')->on('labels');

            $table->integer('year');
            $table->text('source');
            $table->smallInteger('tempo');

            $table->timestamp('played_at');
            $table->timestamp('finished_at');

            $table->bigInteger('played_count');
            $table->boolean('is_active');
            $table->tinyInteger('volume');
            $table->string('unsplash_search_query', 255);

            $table->unique(['author_id', 'album_id', 'title']);
            $table->index(['finished_at']);

            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('songs');
    }
};
