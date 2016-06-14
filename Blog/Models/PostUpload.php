<?php namespace App\Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class PostUpload extends Model {

	protected $table = 'post_uploads';

        protected $fillable=[
        'post_id',
        'type',
        'media_name',
        'media_path',
        'created_at',
        'updated_at'
    ];
}
