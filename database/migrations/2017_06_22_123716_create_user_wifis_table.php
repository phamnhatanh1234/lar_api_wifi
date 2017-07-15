<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserWifisTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('userwifis', function(Blueprint $table)
		{
			$table->string('username');
			$table->primary('username');
			$table->string('password', 30);
			$table->integer('idpricetype')->unsigned();
			$table->foreign('idpricetype')->references('id')->on('priceusertypes');
			//$table->boolean('used');
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
		Schema::drop('userwifis');
	}

}
