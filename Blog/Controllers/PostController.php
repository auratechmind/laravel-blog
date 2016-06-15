<?php

namespace App\Modules\Blog\Controllers;

use App\Modules\Blog\Models\Posts;
use App\Modules\Blog\Models\Category;
use DB;
use App\User;
use Redirect;
use App\Http\Controllers\Controller;
use App\Modules\Blog\Components\PostRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Input;
use File;

class PostController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $posts = Posts::where('active', '1')->orderBy('created_at', 'desc')->paginate(10);
        $title = 'Latest Posts';
        return view('Blog::home')->withPosts($posts)->withTitle($title);
    }

    /**
     * List all blog category
     * @return Response
     */
    public function admin()
    {
        $categorys = DB::table('blog_category')->get();

        return view('Blog::category.category')->with('categorys', $categorys);
    }

    /**
     * Redirect to category add page
     */
    public function add_category()
    {
		return view('Blog::category.addcategory');
    }

    /**
     * Save new created category
     * */
    public function save_category(Request $request)
    {

        $messages = array('required' => 'The category name is required.');

        $v = Validator::make($request->all(),
                ['category_name' => 'required | unique:blog_category'],
                $messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }

        $newcat = $request->get('category_name');
        $status = $request->get('status');
        DB::table('blog_category')->insert(['category_name' => $newcat, 'status' => $status]);

        return redirect('/admin/category');
    }

    /**
     * Redirect to edit page
     */
    public function edit_category($category_id)
    {
		$category = DB::table('blog_category')->where('id', '=', $category_id)->get();
        return view('Blog::category.editcategory')->with('category', $category);
    }

    /**
     * Update blog category
     * */
    public function update_category(Request $request)
    {
		$messages = array('required' => 'The category name is required.');
        $id       = $request->get('catid');

        $v = Validator::make($request->all(),
                ['category_name' => 'required |  unique:blog_category,category_name,'.$id],
                $messages);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }

        $newcat = $request->get('category_name');
        $status = $request->get('status');

        DB::table('blog_category')
            ->where('id', $id)
            ->update(['category_name' => $newcat, 'status' => $status]);

        return redirect('/admin/category');
    }

    /**
     * Category wise post display
     * */
    public function categorywise($category)
    {
		$posts = Posts::where('active', '1')->where('category', 'like',
                '%'.$category.'%')->orderBy('created_at', 'desc')->paginate(5);
        $title = $category.' Posts';

        return view('Blog::home')->withPosts($posts)->withTitle($title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {

        if ($request->user()->can_post()) {
            $data         = DB::table('blog_category')->where('status', 'y')->get();
            $return_array = array();
            if (!empty($data)) {
                foreach ($data as $r) {
                    $return_array[] = array('id' => $r->id, 'name' => $r->category_name);
                }
            }
            $data = json_encode($return_array);

            return view('Blog::posts.create', compact('data'));
        } else {
            return redirect('/')->withErrors('You have not sufficient permissions for writing post');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(PostRequest $request)
    {

        $post            = new Posts();
        $post->title     = $request->get('title');
        $post->body      = $request->get('body');
        $post->slug      = str_slug($post->title);
        $post->author_id = $request->user()->id;
        $post->category  = $request->get('category');
        $post->tag       = $request->get('post_tag');

        $v = Validator::make($request->all(),
                [
                'category' => 'required',
                'title' => 'required',
                'body' => 'required']);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        } else {


            if ($request->has('save')) {
                $post->active = 0;
                $message      = 'Post saved successfully';
            } else {
                $post->active = 1;
                $message      = 'Post published successfully';
            }

            if ($post->save()) {
				$p= base_path().'/app/Modules/Blog/myupload';
				if (!is_dir($p)){
					mkdir($p);
					chmod($p, 0777);
				}
				
                $path = base_path().'/app/Modules/Blog/myupload/'.$post->id;

                if (!is_dir($path)) {
                    mkdir($path);
                    chmod($path, 0777);
                }
                $files     = Input::file('images');
                $imagesStr = Posts::saveMedia($path, $post->id, $files,
                        array('png', 'jpeg', 'jpg', 'gif'), 'IMG_', 'image');


                $files2   = Input::file('videos');
                $videoStr = Posts::saveMedia($path, $post->id, $files2,
                        array('mp4', 'avi', '3gp'), 'VDO_', 'video');


                $files3   = Input::file('otherfiles');
                $OtherStr = Posts::saveMedia($path, $post->id, $files3,
                        array('mp4', 'avi', '3gp', 'docx', 'xls', 'xlsx', 'pdf',
                        'jpeg', 'png'), 'OTHR_', 'other');

                return redirect('edit/'.$post->slug)->withMessage($message);
            }
        }
    }

    /**
     * Description: Download other documents
     * By: Dhara
     * @param type $id
     * @return type
     */
    public function getDownload($id)
    {

        $record = \App\Modules\Blog\Models\PostUpload::find($id);

        $file       = public_path().'/../app/Modules/Blog/myupload'.'/'.$record->post_id.'/'.$record->media_name; //var_dump($file2);exit;
        $path_parts = pathinfo($file);
        $ext        = strtolower($path_parts["extension"]);
        switch ($ext) {
            case "pdf":
                $headers = array("Content-type: application/pdf");
                break;
            case "docx":
                $headers = array("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
                break;
            // add more headers for other content types here
            default;
                $headers = array("Content-type: application/octet-stream");
                break;
        }

        return Response::download($file, $record->media_name, $headers);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($slug)
    {
		$post = Posts::where('slug', $slug)->first();

        if ($post) {
            if ($post->active == false)
                    return redirect('/')->withErrors('requested page not found');
            $comments = $post->comments;
        }
        else {
            return redirect('/')->withErrors('requested page not found');
        }
        return view('Blog::posts.show')->withPost($post)->withComments($comments);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $slug)
    {
        $data         = DB::table('blog_category')->where('status', 'y')->get();
        $return_array = array();
        if (!empty($data)) {
            foreach ($data as $r) {
                $return_array[] = array('id' => $r->id, 'name' => $r->category_name);
            }
        }
        $data = json_encode($return_array);

        $post = Posts::where('slug', $slug)->first();
        if ($post && ($request->user()->id == $post->author_id || $request->user()->is_admin())) {
            $category = array();
            $d        = explode(',', $post->category);
            if (!empty($d)) {
                $i = 0;
                foreach ($d as $r) {
                    $i++;
                    $category[] = array('id' => $i, 'name' => $r);
                }
            }
            $category = json_encode($category);
            return view('Blog::posts.edit')->with('post', $post)->with('data',
                    $data)->with('category', $category);
        } else {
            return redirect('/')->withErrors('you have not sufficient permissions');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {
        $post_id = $request->input('post_id');
        $post    = Posts::find($post_id);

        if ($post && ($post->author_id == $request->user()->id || $request->user()->is_admin())) {
            $title     = $request->input('title');
            $slug      = str_slug($title);
            $duplicate = Posts::where('slug', $slug)->first(); //echo 12;exit;
            if ($duplicate) {
                if ($duplicate->id != $post_id) {
                    return redirect('edit/'.$post->slug)->withErrors('Title already exists.')->withInput();
                } else {
                    $post->slug = $slug;
                }
            }

            $post->tag      = $request->get('post_tag');
            $post->category = $request->get('category');
            $post->title    = $title;
            $post->body     = $request->input('body');
            $post->slug     = $slug;

            $v = Validator::make($request->all(),
                    [
                    'title' => array('Regex:/^[A-Za-z0-9 ]+$/'),
                    'title' => 'required|unique:posts,title,'.$post->id.'|max:255',
                    'category' => 'required',
                    'body' => 'required']);

            if ($v->fails()) {
                return redirect()->back()->withErrors($v->errors());
            } else {
                if ($request->has('save')) {
                    $post->active = 0;
                    $message      = 'Post saved successfully';
                    $landing      = 'edit/'.$post->slug;
                } else {
                    $post->active = 1;
                    $message      = 'Post updated successfully';
                    $landing      = $post->slug;
                }
                if ($post->save()) {
                    $mediaremoved = $request->input('mediaremoved');
                    $deleteStr    = Posts::deleteMedia($mediaremoved);
                    $p= base_path().'/app/Modules/Blog/myupload';
					if (!is_dir($p)){
						mkdir($p);
						chmod($p, 0777);
					}
                    $path         = base_path().'/app/Modules/Blog/myupload/'.$post->id;
                    if (!is_dir($path)) {
                        mkdir($path);
                        chmod($path, 0777);
                    }
                    $files     = Input::file('images');
                    $imagesStr = Posts::saveMedia($path, $post->id, $files,
                            array('png', 'jpeg', 'jpg', 'gif'), 'IMG_', 'image');


                    $files2   = Input::file('videos');
                    $videoStr = Posts::saveMedia($path, $post->id, $files2,
                            array('mp4', 'avi', '3gp'), 'VDO_', 'video');

                    $files3   = Input::file('otherfiles');
                    $OtherStr = Posts::saveMedia($path, $post->id, $files3,
                            array('mp4', 'avi', '3gp', 'docx', 'xls', 'xlsx', 'pdf',
                            'jpeg', 'png'), 'OTHR_', 'other');

                    return redirect($landing)->withMessage($message);
                }
            }
        } else {
            return redirect('/')->withErrors('you have not sufficient permissions');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
		$post = Posts::find($id);
        if ($post && ($post->author_id == $request->user()->id || $request->user()->is_admin())) {
			$files = DB::table('post_uploads')->where('post_id', '=', $post->id)->get();
			foreach($files as $file){
				File::delete('../app/Modules/Blog/myupload/'.$post->id."/".$file->media_name);
			}
            $post->delete();
            $data['message'] = 'Post deleted Successfully';
        } else {
            $data['errors'] = 'Invalid Operation. You have not sufficient permissions';
        }

        return redirect('/posts')->with($data);
    }
    /*
     * Display the posts of a particular user
     *
     * @param int $id
     * @return Response
     */

    public function user_posts($id)
    {
        $posts  = Posts::where('author_id', $id)->where('active', '1')->orderBy('created_at',
                'desc')->paginate(5);
        $title  = User::find($id)->name;
        $mydata = Category::where('status', 'y')->paginate(15);
        $posts->setPath('posts');
        return view('Blog::home')->withPosts($posts)->withTitle($title)->with('mydata',
                $mydata);
    }

	/**
	 * Get all post userwise
	 */ 
    public function user_posts_all(Request $request)
    {
        $user  = $request->user();
        $posts = Posts::where('author_id', $user->id)->orderBy('created_at',
                'desc')->paginate(5);
        $title = $user->name;
        return view('Blog::home')->withPosts($posts)->withTitle($title);
    }

	/**
	 * Get user draft posts
	 */ 
    public function user_posts_draft(Request $request)
    {
        $user  = $request->user();
        $posts = Posts::where('author_id', $user->id)->where('active', '0')->orderBy('created_at',
                'desc')->paginate(5);
        $title = $user->name;
        return view('Blog::home')->withPosts($posts)->withTitle($title);
    }

    /**
     * profile for user
     */
    public function profile(Request $request, $id)
    {
        $data['user'] = User::find($id);
        if (!$data['user']) return redirect('/');

        if ($request->user() && $data['user']->id == $request->user()->id) {
            $data['author'] = true;
        } else {
            $data['author'] = null;
        }
        $data['comments_count']     = $data['user']->comments->count();
        $data['posts_count']        = $data['user']->posts->count();
        $data['posts_active_count'] = $data['user']->posts->where('active', '1')->count();
        $data['posts_draft_count']  = $data['posts_count'] - $data['posts_active_count'];
        $data['latest_posts']       = $data['user']->posts->where('active', '1')->take(5);
        $data['latest_comments']    = $data['user']->comments->take(5);

        return view('Blog::profile', $data);
    }
}
