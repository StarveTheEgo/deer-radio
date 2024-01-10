<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {

    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 128);
            $table->string('field_type');
            $table->json('field_options')->nullable();
            $table->text('description');
            $table->text('value')->nullable();
            $table->boolean('is_encrypted')->default(0);
            $table->integer('ord')->default(1);
            $table->timestamps();

            $table->unique(['key'], '_idx_unique_key');
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
