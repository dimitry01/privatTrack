<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_stats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('campaign_id')->unsigned();
            $table->longText('countries')->nullable();
            $table->longText('isps')->nullable();
            $table->longText('os')->nullable();
            $table->longText('openers')->nullable();
            $table->longText('clickers')->nullable();
            $table->foreign('campaign_id')->references('id')->on('campaigns');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_stats');
    }
}
