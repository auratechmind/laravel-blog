<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 * role column is added into users table
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table)
                {
                        $table->enum('role',['admin','author','subscriber'])->default('author');
                });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
