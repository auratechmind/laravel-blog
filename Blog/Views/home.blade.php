<?php
use Illuminate\Support\Facades\URL;
//echo base_path();
?>

@extends('Blog::layouts.app')

@section('title')
{{$title}}
@endsection

@section('content')

<script type="text/javascript" src="{{ url('../app/Modules/Blog/Assets/js/jquery.infinitescroll.min.js') }}"></script>

<!--<script type="text/javascript" src="{{ asset('js/jquery.infinitescroll.min.js') }}"></script>-->
@if ( !$posts->count() )
There is no post till now. Login and write a new post now!!!
@else
<style>
    #posts img{

        width: 100%;
    }


</style>
<div class="">
    <div id="posts">
	@foreach( $posts as $post )
	<div class="list-group post">
		<div class="list-group-item">
			<h3><a href="{{ url('/'.$post->slug) }}">{{ $post->title }}</a>
				@if(!Auth::guest() && ($post->author_id == Auth::user()->id || Auth::user()->is_admin()))
					@if($post->active == '1')
					<a href="{{ url('edit/'.$post->slug)}}"><button class="btn" style="float: right">Edit Post</button></a>
					@else
					<button class="btn" style="float: right"><a href="{{ url('edit/'.$post->slug)}}">Edit Draft</a></button>
					@endif
				@endif
			</h3>
			<p>{{ $post->created_at->format('M d,Y \a\t h:i a') }} By <a href="{{ url('/user/'.$post->author_id)}}">{{ $post->author->name }}</a></p>
                        <p>Category : {{$post->category}}</p>
		</div>
		<div class="list-group-item">
			<article>
				{!! str_limit($post->body, $limit = 1500, $end = '....... <a href='.url("/".$post->slug).'>Read More</a>') !!}



                        </article>
		</div>
	</div>
	@endforeach
	{!! $posts->render() !!}
        </div>
</div>

<script>

$(document).ready(function() {
    var loading_options = {
        finishedMsg: "<div class='end-msg'>Congratulations! You've reached the end of the internet</div>",
        msgText: "<div class='center'>Loading news items...</div>",
        img: "/assets/img/ajax-loader.gif"
    };

    $('#posts').infinitescroll({
        loading: loading_options,
        navSelector: "ul.pagination",
        nextSelector : "ul.pagination li:last-child a",
        itemSelector: "#posts div.post"
    });
});
</script>
@endif
@endsection

