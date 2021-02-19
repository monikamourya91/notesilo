<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutoApproveSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_approve_settings', function (Blueprint $table) {
            $table->id();
			$table->integer('group_id');
			$table->integer('interval_min');
			$table->integer('all_answered');
			$table->integer('is_email');
			$table->string('when_joined',50);
			$table->integer('friends_in_group');
			$table->string('lives_in',200);
			$table->integer('mutual_friends');
			$table->integer('common_groups');
			$table->string('next_interval',100);
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
        Schema::dropIfExists('auto_approve_settings');
    }
}
