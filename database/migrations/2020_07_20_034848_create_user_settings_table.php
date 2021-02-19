<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
			$table->string("unique_hash");
			$table->string("google_sheet_url");
			$table->tinyInteger("global_autoresponder_status");
			$table->tinyInteger("enable_googlesheet");
			$table->tinyInteger("global_decline_message_status");
			$table->tinyInteger("is_email_send");
			$table->string("ext_version",100);
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
        Schema::dropIfExists('user_settings');
    }
}
