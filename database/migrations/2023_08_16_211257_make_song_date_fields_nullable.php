<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('songs', function (Blueprint $table) {
            $table
                ->timestamp('played_at')
                ->nullable()
                ->change();

            $table
                ->timestamp('finished_at')
                ->nullable()
                ->change();
        });
    }

    public function down()
    {
        Schema::table('songs', function (Blueprint $table) {
            $table
                ->timestamp('played_at')
                ->nullable(false)
                ->change();

            $table
                ->timestamp('finished_at')
                ->nullable(false)
                ->change();
        });
    }
};
