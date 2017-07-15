<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceUserTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('priceusertypes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->bigInteger('price');
			$table->datetime('dateupdate');
			$table->integer('idusertype')->unsigned();
			$table->foreign('idusertype')->references('id')->on('usertypes');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('priceusertypes');
	}

}
