<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostUploadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('post_uploads', function(Blueprint $table)
		{
			$table->increments('id');
                        $table -> integer('post_id') -> default(0);
                        $table->string('type');
                        $table->string('media_name');
                        $table->string('media_path');
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
		Schema::drop('post_uploads');
	}

}
