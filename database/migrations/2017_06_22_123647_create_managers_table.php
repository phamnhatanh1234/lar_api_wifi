<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('managers', function(Blueprint $table)
		{
			$table->string('username');
			$table->primary('username');
			$table->string('password', 60);
			$table->rememberToken();
			$table->string('name');
			$table->integer('idrole')->unsigned();
			$table->foreign('idrole')->references('id')->on('roles');
			$table->boolean('canuse');
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
		Schema::drop('managers');
	}

}
