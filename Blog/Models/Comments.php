<?php namespace App\Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model {

	//comments table in database
	protected $guarded = [];
	
	// user who commented
	public function author()
	{
		return $this->belongsTo('App\User','from_user');
	}
	
	/**
	 * relation with post 
	 */ 
	public function post()
	{
		return $this->belongsTo('App\Modules\Blog\Models\Posts','on_post');
	}

}
