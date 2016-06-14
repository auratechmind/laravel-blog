<?php
use Illuminate\Support\Facades\URL;

?>
@extends('Blog::layouts.app',['title' => 'Your Title Goes Here'])

@section('title')
Add New Post
@endsection
<style>
    #mceu_18-open{

       // display:none;
    }

</style>
@section('content')

<script type="text/javascript" src="{{ asset('../app/Modules/Blog/Assets/js/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript" src="{{ url('../app/Modules/Blog/Assets/js/jquery.tokeninput.js') }}"></script>

<link href="{{ url('../app/Modules/Blog/Assets/css/token-input.css') }}" rel="stylesheet">
<link href="{{ url('../app/Modules/Blog/Assets/css/token-input-facebook.css') }}" rel="stylesheet">

<form action="{{ url('/') }}/new-post" method="post" enctype="multipart/form-data">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="form-group">
            Title : <input value="{{ old('title') }}" placeholder="Enter title here" type="text" id="title" name = "title" class="form-control" />
                <p style="color: red">{{ $errors->first('title')}}p>
        </div>

        <div class="form-group">
             Category:  	<input value="{{ old('category') }}" placeholder="Enter category here" id="category" type="text" name = "category" class="form-control" />
                <p style="color: red">{{ $errors->first('category')}}</p>
        </div>

        <div class="form-group">
            Tag:       <input type="text" name="post_tag" class="form-control">
        </div>
        
	<div class="form-group">
		<textarea name='body'class="form-control">{{ old('body') }}</textarea>
                <p style="color: red">{{ $errors->first('body')}}</p>
	</div>
        
       <div class="form-group">
		Upload Images:
           <input id="pic" type="file" name="images[]" multiple="true">
      
	</div>
        <div class="form-group">
		Upload Videos:
           <input id="video" type="file" name="videos[]" multiple="true">
      
	</div>
         <div class="form-group">
		Upload Other Files:
           <input id="otherfile" type="file" name="otherfiles[]" multiple="true">
      
	</div>
        
	<input type="submit" name='publish' class="btn btn-success" value = "Publish"/>
	<input type="submit" name='save' class="btn btn-default" value = "Save Draft" />
</form>
<?php //print_r($data); ?>
<script>

tinymce.init({
		selector : "textarea",
		plugins : ["advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste jbimages"],
		toolbar : "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
	});


//$(document).ready(function() {
 $(function () {
    $("#category").tokenInput(<?php echo $data ?>, {
        theme: 'facebook',
        preventDuplicates: true,
        tokenValue: 'name',
        onResult: function (item) {

            if ($.isEmptyObject(item)) {
                return [{id: '0', name: $("tester").text()}]
            } else {
                return item;
            }
        },
    });
});

$("#pic").change(function() {
    var val = $(this).val();

    switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
        case 'jpg': case 'png':
            break;
        default:
            $(this).val('');
            alert("plese upload png or jpg file only!!!");
            break;
    }
}); 

$("#video").change(function() {
    var val = $(this).val();

    switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
        case 'avi': case '3gp': case 'mp4':
            break;
        default:
            $(this).val('');
            alert("plese upload avi,3gp or mp4 file only!!!");
            break;
    }
});

$("#otherfile").change(function() {

    var val = $(this).val();

    switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
        case 'xls': case 'pdf': case 'doc':
            break;
        default:
            $(this).val('');
            alert("plese upload xls,pdf or doc files only!!!");
            break;
    }
});
</script>
@endsection
