<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('author_id')->unsigned();
            $table->foreign('author_id')->references('id')->on('authors');

            $table->string('title', 255);
            $table->integer('year');
            $table->timestamps();

            $table->unique(['author_id', 'title']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('albums');
    }
};
