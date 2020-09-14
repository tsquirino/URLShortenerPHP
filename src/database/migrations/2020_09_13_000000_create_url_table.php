<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateURLTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('URL', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shortened_url')->unique()->nullable(false);
            $table->string('original_url')->nullable(false);
            $table->string('expiration_date')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('URL');
    }
}
