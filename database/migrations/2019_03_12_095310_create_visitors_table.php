<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
            $table->string('full_name');
            $table->string('hash_md5');
            $table->string('is_proxy')->nullable();
            $table->string('ip')->nullable();
            $table->string('os')->nullable();
            $table->string('device')->nullable();
            $table->string('browser')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('isp')->nullable();
            $table->text('user_agent')->nullable();
            $table->bigInteger('file_id')->unsigned();
            $table->timestamps();
            $table->foreign('file_id')->references('id')->on('files');
            $table->index('hash_md5');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitors');
    }
}
