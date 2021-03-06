<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('entries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->dateTime('liked_date')->nullable();
			$table->string('url', 2000);
			$table->string('guid')->nullable();
			$table->mediumText('title')->nullable();
			$table->text('description')->nullable();
			$table->text('content')->nullable();
			$table->dateTime('date_published')->nullable();
			$table->text('keywords')->nullable();
			$table->text('entities')->nullable();
			$table->text('image')->nullable();
			$table->text('favicon')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('entries');
	}

}
