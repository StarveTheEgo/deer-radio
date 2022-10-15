<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('label_links', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('label_id')->unsigned();
            $table->foreign('label_id')->references('id')->on('labels');
            $table->string('url', 255);
            $table->timestamps();

            $table->unique(['label_id', 'url']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('label_links');
    }
};
