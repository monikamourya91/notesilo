<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_leads', function (Blueprint $table) {
            $table->id();
			$table->integer('user_id');
			$table->integer('group_id');
			$table->string('full_name');
			$table->string('first_name');
			$table->string('last_name');
			$table->string('profile_url');
			$table->string('joined_date');
			$table->string('ques_one');
			$table->string('ans_one');
			$table->string('ques_two');
			$table->string('ans_two');
			$table->string('ques_three');
			$table->string('ans_three');
			$table->string('location');
			$table->string('works_at');
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
        Schema::dropIfExists('all_leads');
    }
}
