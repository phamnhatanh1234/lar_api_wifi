<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('usertypes', function(Blueprint $table)
		{
				$table->increments('id');
				$table->string('name', 30);
				$table->integer('duration');
				$table->integer('iddevice')->unsigned();
				$table->foreign('iddevice')->references('id')->on('devices');
				$table->datetime('dateupdateprice');
				$table->timestamps('created_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('usertypes');
	}

}
