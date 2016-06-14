<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

Route::controllers([
	'auth' => 'App\Http\Controllers\Auth\AuthController',
	'password' => 'App\Http\Controllers\Auth\PasswordController',
]);


Route::get('/admin/category', [ 'middleware' => ['App\Modules\Blog\Middleware\Admin'],'uses' => 'App\Modules\Blog\Controllers\PostController@admin']);
Route::get('/category/edit/{id}', [ 'middleware' => ['App\Modules\Blog\Middleware\Admin'],'uses' => 'App\Modules\Blog\Controllers\PostController@edit_category']);

Route::post('/category/update', [ 'middleware' => ['App\Modules\Blog\Middleware\Admin'],'uses' => 'App\Modules\Blog\Controllers\PostController@update_category']);

Route::get('/category/add', [ 'middleware' => ['App\Modules\Blog\Middleware\Admin'],'uses' => 'App\Modules\Blog\Controllers\PostController@add_category']);

Route::post('/category/save', [ 'middleware' => ['App\Modules\Blog\Middleware\Admin'],'uses' => 'App\Modules\Blog\Controllers\PostController@save_category']);

 Route::get('/posts', ['as' => 'home', 'uses' => 'App\Modules\Blog\Controllers\PostController@index']);
Route::group(['middleware' => ['auth'],'namespace' => 'App\Modules\Blog\Controllers'], function()
{
 Route::get('new-post',['uses' => 'PostController@create']);
 Route::post('new-post',['uses' => 'PostController@store']);
 Route::get('edit/{slug}','PostController@edit');
 Route::post('update','PostController@update');
 Route::get('delete/{id}','PostController@destroy');
 Route::get('my-all-posts','PostController@user_posts_all');
 Route::get('my-drafts','PostController@user_posts_draft');
 Route::get('downloadFile/{id}','PostController@getDownload');
 Route::post('comment/add','CommentController@store');
 Route::post('comment/delete/{id}','CommentController@distroy');

});

Route::group(['namespace' => 'App\Modules\Blog\Controllers'], function()
{
Route::get('user/{id}','PostController@profile')->where('id', '[0-9]+');
Route::get('user/{id}/posts','PostController@user_posts')->where('id', '[0-9]+');
Route::get('/{slug}',['as' => 'post', 'uses' => 'PostController@show'])->where('slug', '[A-Za-z0-9-_]+');
Route::get('comment/dlt/{id}','CommentController@dlt');
Route::get('categorywise/{id}','PostController@categorywise');

});
?>
