<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinkedFbGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linked_fb_groups', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('userId');
			$table->string('fb_group_id',100);
			$table->string('group_name',100);
			$table->text('decline_message');
			$table->text('decline_message_two');
			$table->text('decline_message_three');
			$table->tinyInteger('decline_interval');
			$table->tinyInteger('decline_limit');
			$table->tinyInteger('decline_start_time');
			$table->integer('decline_random_status');
			$table->tinyInteger('decline_message_status');
			$table->text('auto_decline_keywords');
			$table->integer('auto_decline_interval');
			$table->tinyInteger('enable_auto_decline');
			$table->tinyInteger('autoresponder_status');
			$table->tinyInteger('email_index');
			$table->tinyInteger('auto_approve');
			$table->text('welcome_message_one');
			$table->text('welcome_message_two');
			$table->text('welcome_message_three');
			$table->text('welcome_message_four');
			$table->text('welcome_message_five');
			$table->tinyInteger('welcome_interval');
			$table->tinyInteger('welcome_limit');
			$table->tinyInteger('welcome_start_time');
			$table->tinyInteger('welcome_random_status');
			$table->tinyInteger('welcome_message_status');
			$table->tinyInteger('tag_status');
			$table->string('welcome_post_id',500);
			$table->tinyInteger('tag_all');
			$table->tinyInteger('tag_random_status');
			$table->string('tag_message_one',1000);
			$table->string('tag_message_two',1000);
			$table->string('tag_message_three',1000);
			$table->string('tag_message_four',1000);
			$table->string('tag_message_five',1000);
			$table->tinyInteger('status');
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
        Schema::dropIfExists('linked_fb_groups');
    }
}
