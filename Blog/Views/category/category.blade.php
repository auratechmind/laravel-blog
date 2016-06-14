<?php
use Illuminate\Support\Facades\URL;
//echo base_path();
?>

@extends('Blog::layouts.app')

@section('title')
Category <div style="float:right"><a href="{{ url('/category/add/') }}">Add New</a></div>
@endsection

@section('content')

@if ( empty($categorys) )
There is no post till now. Login and write a new post now!!!
@else

<div class="">    
    <div id="posts">
	@foreach( $categorys as $category )
	<div class="list-group post">
		<div class="list-group-item">
				<p>Category : {{$category->category_name}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </p>
				<?php if($category->status=="y"){$status="Active";}else{$status="Block";}?>
				<p>Status : {{$status}} </p>

				<a href ="{{ url('/category/edit/'.$category->id) }}">Edit </a>
		</div>
	</div>
	@endforeach
        </div>
</div>
@endif
@endsection


