<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bills', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('idstudent')->unsigned();
			$table->foreign('idstudent')->references('id')->on('students');
			$table->string('userwifiname');
			$table->foreign('userwifiname')->references('username')->on('userwifis');
			$table->string('managername');
			$table->foreign('managername')->references('username')->on('managers');
			$table->datetime('datebuy');
			$table->datetime('dateexpired');
			$table->datetime('datecanrefund');
			$table->boolean('refund');
			$table->datetime('daterefund');
			$table->boolean('expired');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bills');
	}

}
