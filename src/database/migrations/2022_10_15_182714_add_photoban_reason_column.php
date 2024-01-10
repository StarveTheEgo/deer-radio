<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up()
    {
        Schema::table('photobans', function (Blueprint $table) {
            $table->string('reason', 128);
        });
    }

    public function down()
    {
        Schema::table('photobans', function (Blueprint $table) {
            $table->dropColumn('reason');
        });
    }
};
