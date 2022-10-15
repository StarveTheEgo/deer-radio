<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->unique(['name']);
        });
    }

    public function down()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }
};
