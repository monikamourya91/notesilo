<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutorespondersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autoresponders', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('autoresponder_list_id');
			$table->bigInteger('userId');
			$table->integer('group_id');
			$table->string('api_url');
			$table->text('api_key');
			$table->string('app_path');
			$table->bigInteger('tagid');
			$table->string('tag_name');
			$table->string('field_four');
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
        Schema::dropIfExists('autoresponders');
    }
}
