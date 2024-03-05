<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('outputs', function (Blueprint $table) {
            $table->string('stream_state', 16)->index();
            $table->timestamp('prepared_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('outputs', function (Blueprint $table) {
            $table->dropColumn('stream_state');
            $table->dropColumn('prepared_at');
        });
    }
};
