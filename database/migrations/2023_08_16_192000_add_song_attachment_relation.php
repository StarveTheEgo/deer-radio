<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up()
    {
        Schema::table('songs', function (Blueprint $table) {
            $table
                ->unsignedInteger('song_attachment_id')
                ->nullable();

            $table
                ->foreign('song_attachment_id')
                ->references('id')
                ->on('attachments');
        });
    }

    public function down()
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->dropColumn('song_attachment_id');
        });
    }
};
