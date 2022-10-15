<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->removeColumn('website');
        });

        Schema::table('labels', function (Blueprint $table) {
            $table->removeColumn('website');
        });
    }

    public function down()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->string('website', 255)->comment('Back compatibility');
        });

        Schema::table('labels', function (Blueprint $table) {
            $table->string('website', 255)->comment('Back compatibility');
        });
    }
};
