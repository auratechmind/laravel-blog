<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogCategory extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blog_category', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->string('category_name');
                        $table->enum('status', ['y', 'n'])->default('y');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('blog_category');
	}

}
