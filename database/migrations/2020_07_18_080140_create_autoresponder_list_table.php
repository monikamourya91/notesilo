<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutoresponderListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autoresponder_list', function (Blueprint $table) {
            $table->id();
			
			$table->string('responder_type');
			$table->string('responder_key',200);
			$table->string('field_one_validation',1000);
			$table->string('field_two_validation',1000);
			$table->string('field_three_validation',1000);
			$table->text('notice');
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
        Schema::dropIfExists('autoresponder_list');
    }
}
