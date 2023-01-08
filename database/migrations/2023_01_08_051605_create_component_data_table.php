<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {

    public function up()
    {
        Schema::create('component_data', function (Blueprint $table) {
            $table->id();
            $table->string('component', 128);
            $table->string('field', 128);
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['component', 'field'], '_idx_unique_field');
        });
    }

    public function down()
    {
        Schema::dropIfExists('component_data');
    }
};
