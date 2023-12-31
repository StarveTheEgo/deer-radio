<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outputs', function (Blueprint $table) {
            $table->id();
            $table->string('output_name', 64)->index();
            $table->string('driver_name', 64)->index();
            $table->json('driver_config');
            $table->boolean('is_active')->index();
            $table->timestamps();

            $table->unique(['output_name', 'driver_name'], 'unique_output');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outputs');
    }
};
