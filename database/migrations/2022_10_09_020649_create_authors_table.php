<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->boolean('is_active');
            $table->timestamp('played_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->bigInteger('played_count');
            $table->string('unsplash_search_query', 64)->nullable();
            $table->index(['name']);
            $table->index(['played_at']);
            $table->index(['finished_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('authors');
    }
};
