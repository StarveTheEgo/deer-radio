<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up()
    {
        Schema::create('setting_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->string('title', 64);
            $table->timestamps();

            $table->unique(['name']);
            $table->unique(['title']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('setting_groups');
    }
};
