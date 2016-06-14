<?php
use Illuminate\Support\Facades\URL;

?>

@extends('Blog::layouts.app')

@section('title')
Edit Post
@endsection

@section('content')
<script type="text/javascript" src="{{ asset('../app/Modules/Blog/Assets/js/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript" src="{{ url('../app/Modules/Blog/Assets/js/jquery.tokeninput.js') }}"></script>

<link href="{{ url('../app/Modules/Blog/Assets/css/token-input.css') }}" rel="stylesheet">
<link href="{{ url('../app/Modules/Blog/Assets/css/token-input-facebook.css') }}" rel="stylesheet">
<script type="text/javascript">
	tinymce.init({
		selector : "textarea",
		plugins : ["advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste jbimages"],
		toolbar : "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
	    //plugins: "media"
	});

 $("#other").change(function() {
     //alert("ok");
    var val = $(this).val();

    switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
        case 'xls': case 'pdf': case 'doc':
            //alert("an image");
            break;
        default:
            $(this).val('');
            // error message here
            alert("plese upload xls,pdf or doc files only!!!");
            break;
    }
});

        $(function () {
    $("#category").tokenInput(<?php echo $data ?>, {
        prePopulate: <?php echo $category; ?>,
        theme: 'facebook',
        preventDuplicates: true,
        tokenValue: 'name',
       // Pre-fill: '[{id: 3}, {id: 1}]',
        onResult: function (item) {
//            $.each(results, function (index, value) {
//                value.name = value.name;
//            });
//
//            return results;

            if ($.isEmptyObject(item)) {
                return [{id: '0', name: $("tester").text()}]
            } else {
                return item;
            }
        }
//        onAdd: function (item) {
//            alert("Added " + item.name);
//        },
    });

   // $("#category").tokenInput("add", {id: x, name: y});
});


  


</script>

<form method="post" action='{{ url("/update") }}' enctype="multipart/form-data">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="post_id" value="{{ $post->id }}{{ old('post_id') }}">
	<div class="form-group">
		<input placeholder="Enter title here" type="text" name = "title" class="form-control" value="@if(!old('title')){{$post->title}}@endif{{ old('title') }}"/>
                <p style="color: red">{{ $errors->first('title')}}</p>
	</div>

        <div class="form-group">
             Category:  	<input value="{{$post->category}}" placeholder="Enter category here" id="category" type="text" name = "category" class="form-control" />
                <p style="color: red">{{ $errors->first('category')}}</p>
        </div>

          <div class="form-group">
            Tag:       <input type="text" value="{{$post->tag}}" name="post_tag" class="form-control">

        </div>

	<div class="form-group">
		<textarea name='body'class="form-control">
			@if(!old('body'))
			{!! $post->body !!}
			@endif
			{!! old('body') !!}
		</textarea>
	</div>


        






       
        <input hidden name="mediaremoved" id="mediaremoved" type="text" value="">
       
        
        
        <div>
		<!--{!! $post->image !!} -->
                <?php 
                $j=1;
                foreach($post->imagesMedia as $img){
                  
                    if(!empty($img)){
                        $path = $img->media_path;
                ?>
                <div id="imageDiv<?php echo $img->id;?>">
                        <img id="image<?php echo $j;?>" src="{{ url('../app/Modules/Blog/myupload/').'/'.$post->id.'/'.$img->media_name }}" width="30%" />

                       </div>
                    
                     <button type="button" id="<?php echo $img->id;?>" onclick="removedmedia(this.id)">removemedia</button>
                   <?php } $j++; } ?>

        </div>


       <div>
		   
		    <div>
		<!--{!! $post->image !!} -->
                <?php
//                $postId=$post->id;
//                $video=$post->video;
//
//                $videos=  explode(",", $video);
                $i=1;
                foreach($post->videoMedia as $vdo){
                   
                    if(!empty($vdo)){
                        $path = $vdo->media_path;
                ?>
             <div id="imageDiv<?php echo $vdo->id;?>">
     <video id="video<?php echo $i;?>" src="{{ url('../app/Modules/Blog/myupload/').'/'.$post->id.'/'.$vdo->media_name }}" width="30%" controls>
         
    </div>
  
   <button type="button" id="<?php echo $vdo->id;?>" onclick="removedmedia(this.id)">removemedia</button>
<?php } $i++; } ?>

        </div>	
    <!--{!! $post->image !!} -->
    <?php
    $pdfSource = '../app/Modules/Blog/Assets/images/pdf.png';
    $xlsSource = '../app/Modules/Blog/Assets/images/xls.png';
    $docSource = '../app/Modules/Blog/Assets/images/doc.png';
    foreach ($post->otherMedia as $other) {

        if (!empty($other)) {
            $path = $other->media_path;
            $path_parts= pathinfo($other->media_name);
            $alt = (($path_parts['extension']=='pdf')?$pdfSource:(($path_parts['extension']=='docx' || $path_parts['extension']=='doc')?$docSource:$xlsSource));
            //echo $alt;
            ?>
<div id="imageDiv<?php echo $other->id;?>">
    <img src={{asset($alt)}} style="width: 50px; height: 70px">
</div>
   <button type="button" id="<?php echo $other->id;?>" onclick="removedmedia(this.id)">removemedia</button>
            <?php
            $i++;
        }
    }
    ?>

</div>


        <input hidden name="imageremoved" id="imageremoved" type="text" value="">
                Upload Images:
           <input id="pic" type="file" name="images[]" multiple="true">
    
       
		Upload Videos:
           <input id="video" type="file" name="videos[]" multiple="true">

            Upload Other Files:
           <input id="other" type="file" name="otherfiles[]" multiple="true">
   
         
	@if($post->active == '1')
	<input type="submit" name='publish' class="btn btn-success" value = "Update"/>
	@else
	<input type="submit" name='publish' class="btn btn-success" value = "Publish"/>
	@endif
	<input type="submit" name='save' class="btn btn-default" value = "Save As Draft" />
	<a href="{{  url('delete/'.$post->id.'?_token='.csrf_token()) }}" class="btn btn-danger">Delete</a>
</form>

<script>



function removedmedia(i){
   // event.preventDefault();
    document.getElementById("imageDiv"+i).style.display = "none";
     document.getElementById(i).style.display = "none";
   var vd=  document.getElementById("mediaremoved");
   vd.value=vd.value+i+",";
}

 $("#other").change(function() {
    
    var val = $(this).val();

    switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
        case 'xls': case 'pdf': case 'doc':
            //alert("an image");
            break;
        default:
            $(this).val('');
            // error message here
            alert("plese upload xls,pdf or doc files only!!!");
            break;
    }
});


</script>

@endsection
