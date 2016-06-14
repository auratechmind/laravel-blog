<?php
use Illuminate\Support\Facades\URL;
?>

@extends('Blog::layouts.app')

@section('title')
Edit Category
@endsection

@section('content')

@if ( empty($category) )
There is no post till now. Login and write a new post now!!!
@else

<div class="">
    <div id="posts">
	@foreach( $category as $cat )
	<div class="list-group post">
		<div class="list-group-item">
			<form action="{{ url('/category/update') }}" method="post" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				
				<div class="form-group">
					Category Name : <input value="{{ $cat->category_name }}"  type="text"  name = "category_name" class="form-control" />
					<p style="color: red">{{ $errors->first('category_name')}} </p>
				</div>

				<div class="form-group">
				   <?php  if($cat->status=="y"){ ?>

					Category Status : <select name="status" class="form-control">

									<option value="y" selected="true">Active</option>
									<option value="n">Block</option>
								 </select>
						<?php } else { ?>


					Category Status : <select name="status" class="form-control">

									<option value="y" >Active</option>
									<option value="n" selected="true">Block</option>
								 </select>
						<?php         } ?>
				</div>
				
				<div hidden="true" class="form-group">
				<input value="{{ $cat->id }}"  type="text"  name = "catid" class="form-control" />
				</div>
				<input type="submit" name='update' class="btn btn-success" value = "update"/>
		   </form>
		</div>

	</div>
	@endforeach
        </div>
</div>
@endif
@endsection


