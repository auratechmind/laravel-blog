<?php namespace App\Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Blog\Models\PostUpload;

class Posts extends Model {

	//posts table in database
	protected $guarded = [];
	
	/**
	 * Relation with comments table
	 */ 
	public function comments()
	{
		return $this->hasMany('App\Modules\Blog\Models\Comments','on_post');
	}
	
	/**
	 * Relation with user table
	 */ 
	public function author()
	{
		return $this->belongsTo('App\User','author_id');
	}

	/**
	 * Relation with post_upload table. get all media which has type=image
	 */ 
	public function imagesMedia()
	{
		return $this->hasMany('App\Modules\Blog\Models\PostUpload','post_id')->where('type','=','image');
	}

	/**
	 * Relation with post_upload table. get all media which has type=video
	 */ 
	public function videoMedia()
	{
		return $this->hasMany('App\Modules\Blog\Models\PostUpload','post_id')->where('type','=','video');
	}

	/**
	 * Relation with post_upload table. get all media which has type=other
	 */ 
	public function otherMedia()
	{
		return $this->hasMany('App\Modules\Blog\Models\PostUpload','post_id')->where('type','=','other');
	}
        
    /**
     * Get twitter count
     */     
	public static function getTwittercount( $url )
	{
		 $url = 'http://cdn.api.twitter.com/1/urls/count.json?url=' . urlencode('##');

			return 0; // else zed
	}
        
    /**
     * Get facebook count
     */     
	public static function getFacebookcount($url)
	{
		$fql  = "SELECT share_count, like_count, comment_count ";
		$fql .= " FROM link_stat WHERE url = '$url'";

		$fqlURL = "https://api.facebook.com/method/fql.query?format=json&query=" . urlencode($fql);

		// Facebook Response is in JSON
		$response = file_get_contents($fqlURL);
		return json_decode($response);
	}
        
    /**
     * Get Facebook comments
     */     
	public static function getFacebookComments( $url )
	{
		 $fql2=" SELECT post_fbid, fromid, object_id, text, time  FROM comment WHERE is_private = 0 AND object_id IN (SELECT comments_fbid FROM link_stat WHERE url = '$url')";

		 $fq="SELECT comments_fbid FROM link_stat WHERE url = '$url'";
		 $fqlURL2 = "https://api.facebook.com/method/fql.query?format=json&query=" . urlencode($fql2);

		   // Facebook Response is in JSON
		 $response2 = file_get_contents($fqlURL2);
		 return json_decode($response2);
	}

	/**
	 * Save uploaded media. used in post create/update 
	 */ 
	public static function saveMedia($path, $postId, $obj, $mimeArray, $prefix, $type)
	{
		$i=0;
		$str = '';
		foreach ($obj as $row) {

				if (!empty($row)) {
				   
					$ext = $row->getClientOriginalExtension();
				   
					if(in_array($ext, $mimeArray)){
				   
						$imageName = $prefix.$i.'_'.date('dmY').'_'.(time()+$i).'.'.$ext;
						$row->move($path, $imageName);

						$model = new PostUpload();
						$model->post_id = $postId;
						$model->type = $type;
						$model->media_name = $imageName;
						$model->media_path = 'myupload/'.$postId.'/'.$imageName;
						$model->created_at = date('Y-m-d H:i:s');
						$model->save();
						$i++;
						$str="done";
					}
				}
			}

	   return $str;
	}

	/**
	 * Delete media when removed in update.
	 */ 
    public static function deleteMedia($mediaremoved){
		  
			 $arrayofmediaid= explode(",", $mediaremoved); 

              foreach ($arrayofmediaid as $id){
                    if(!empty($id)){
                        $dm = PostUpload::find($id);
                        if (!is_null($dm)) {
                                  $dm->delete();
                                        }
				 }
			 }
				return "media deleted successfully!!";
	  }
}
