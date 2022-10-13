<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up()
    {
        Schema::create('author_links', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('author_id')->unsigned();
            $table->foreign('author_id')->references('id')->on('authors');
            $table->string('url', 255);
            $table->timestamps();

            $table->unique(['author_id', 'url']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('author_links');
    }
};
