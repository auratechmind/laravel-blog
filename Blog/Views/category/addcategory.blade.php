<?php
use Illuminate\Support\Facades\URL;
?>

@extends('Blog::layouts.app')

@section('title')
Add New Category
@endsection

@section('content')


<div class="">
    <div id="posts">
	
	<div class="list-group post">
		<div class="list-group-item">


		   <form action="{{ url('/category/save') }}" method="post" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="form-group">
					Category Name : <input value=""  type="text"  name = "category_name" class="form-control" />
							<p style="color: red">{{ $errors->first('category_name')}} </p>
				</div>


				<div class="form-group">

						Category Status : 
						<select name="status" class="form-control">

                        <option value="y" selected="true">Active</option>
                        <option value="n">Block</option>
                       </select>

				</div>

				<input type="submit" name='update' class="btn btn-success" value = "submit"/>
             </form>
		</div>

	</div>
	
</div>
</div>

@endsection


