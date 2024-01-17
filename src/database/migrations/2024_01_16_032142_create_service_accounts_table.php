<?php

declare(strict_types=1);

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
        Schema::create('service_accounts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('account_name', 64)->index();
            $table->string('service_name', 64)->index();

            $table->unsignedBigInteger('access_token_id');
            $table->foreign('access_token_id')->references('id')->on('access_tokens');

            $table->boolean('is_active');
            $table->timestamps();

            $table->unique(['user_id', 'account_name'], 'unique_account');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_accounts');
    }
};
