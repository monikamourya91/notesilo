<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbGroupSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_group_settings', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('group_id');
			$table->bigInteger('userId');
			$table->bigInteger('autoresponder_id');
			$table->string('google_sheet_url',500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fb_group_settings');
    }
}
