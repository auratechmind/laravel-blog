@extends('Blog::layouts.app')
<script type="text/javascript" src="//newsharecounts.s3-us-west-2.amazonaws.com/nsc.js"></script>
<style>
    #postbody img{

        width: 100%;
    }
</style>
@section('title')
@if($post)
{{ $post->title }}
@if(!Auth::guest() && ($post->author_id == Auth::user()->id || Auth::user()->is_admin()))

<a href="{{ url('edit/'.$post->slug)}}"><button class="btn" style="float: right">Edit Post</button></a>

@endif

<!-- {{$post->category}} -->
@else
Page does not exist
@endif
@endsection

@section('title-meta')
<p>{{ $post->created_at->format('M d,Y \a\t h:i a') }} By <a href="{{ url('/user/'.$post->author_id)}}">{{ $post->author->name }}</a></p>
<p> Category:{{$post->category}}</p>
<p> Tag:{{$post->tag}}</p>

@endsection

@section('content')



@if($post)
<div id="postbody">
    {!! $post->body !!}
    <a hidden href="#" onclick="var x = 'http:' + document.getElementById('myvideo').getAttribute('src');
            // alert(x);
            window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(x), 'facebook-share-dialog', 'width=626,height=436');
            return false;">Share on Facebook</a>
</div>	


<div>
    <!--{!! $post->image !!} -->
    <?php
    $i = 1;
    foreach ($post->imagesMedia as $img) {

        if (!empty($img)) {
            $path = $img->media_path;
            ?>
            <img id="image<?php echo $i; ?>" style="width:200px;height:150px;"src="{{ url('../app/Modules/Blog/myupload/').'/'.$post->id.'/'.$img->media_name }}">
            <a href="#" id="<?php echo $i; ?>" onclick="share(this.id)">Share on Facebook</a>
            <?php
            $i++;
        }
    }
    ?>

</div>	


<div>
    <!--{!! $post->image !!} -->
    <?php
    $j = 1;
    foreach ($post->videoMedia as $vdo) {
        if (!empty($vdo)) {
            $path = $vdo->media_path;
            ?>


            <div id="videoDiv">
                <video id="video<?php echo $j; ?>" src="{{ url('../app/Modules/Blog/myupload/').'/'.$post->id.'/'.$vdo->media_name }}" width="70%" controls>
            </div>
            <a href="#" onclick="var x = document.getElementById('video<?php echo $j; ?>').getAttribute('src');//alert(x);
                    window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(x), 'facebook-share-dialog', 'width=626,height=436');
                    return false;">Share on Facebook</a>

        <?php
        }

        $j++;
    }
    ?>

</div>

<div>
    <!--{!! $post->image !!} -->
    <?php
    $pdfSource = '../app/Modules/Blog/Assets/images/pdf.png';
    $xlsSource = '../app/Modules/Blog/Assets/images/xls.png';
    $docSource = '../app/Modules/Blog/Assets/images/doc.png';
    foreach ($post->otherMedia as $other) {

        if (!empty($other)) {
            $path       = $other->media_path;
            $path_parts = pathinfo($other->media_name);
            $alt        = (($path_parts['extension'] == 'pdf') ? $pdfSource : (($path_parts['extension']
                    == 'docx' || $path_parts['extension'] == 'doc') ? $docSource
                            : $xlsSource));
            ?>

            <a href="downloadFile/<?php echo $other->id; ?>" id="<?php echo $other->id; ?>"><img src={{asset($alt)}} style="width: 50px; height: 70px"></a>
            <a href="#" id="<?php echo $other->id; ?>" onclick="share(this.id)">Share on Facebook</a>
            <?php
            $i++;
        }
    }
    ?>

</div>

<div>
    <h2>Leave a comment</h2>
</div>
@if(Auth::guest())
<p>Login to Comment</p>
@else
<div class="panel-body">
    <form method="post" action="{{ url("/comment/add") }}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="on_post" value="{{ $post->id }}">
        <input type="hidden" name="slug" value="{{ $post->slug }}">
        <div class="form-group">
            <textarea required="required" placeholder="Enter comment here" name = "body" class="form-control"></textarea>
        </div>
        <input type="submit" name='post_comment' class="btn btn-success" value = "Post"/>
    </form>
</div>
@endif

<div>
    @if($comments)
    <ul style="list-style: none; padding: 0">
        @foreach($comments as $comment)
        <li class="panel-body">
            <div class="list-group">
                <div class="list-group-item">
                    <h3>{{ $comment->author->name }}</h3>
                    <p>{{ $comment->created_at->format('M d,Y \a\t h:i a') }}</p>



                    @if(!Auth::guest() && ($post->author_id == Auth::user()->id || Auth::user()->is_admin()|| $comment->from_user == Auth::user()->id))
                    <a href="{{ url("/comment/dlt/$comment->id") }}">  <button>delete</button> </a>
                    @endif



                </div>
                <div class="list-group-item">
                    <p>{{ $comment->body }}</p>
                </div>
            </div>
        </li>
        @endforeach
        <?php
        $link = \App\Modules\Blog\Components\GeneralFunctions::curPageURL();
        ?>
        @if(!Auth::guest())
        <li>
            <a href="#" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(location.href), 'facebook-share-dialog', 'width=626,height=436');
            return false;">Share on Facebook</a>
        </li>
        <li>

            <?php
            $fb         = \App\Modules\Blog\Models\Posts::getTwittercount($link);
            $tc         = \App\Modules\Blog\Models\Posts::getFacebookcount($link);
            $fbcomments = \App\Modules\Blog\Models\Posts::getFacebookComments($link);
            // var_dump($tc);
            if (isset($tc['0']->share_count)) {
                echo "facebook share count:".$tc['0']->share_count;
            }
            ?>
        </li>
        <li>

            <script type="text/javascript" src="//newsharecounts.s3-us-west-2.amazonaws.com/nsc.js"></script>
            <a href="https://twitter.com/share" class="twitter-share-button" data-url="https://dev.twitter.com/rest/collections" data-via="Your twitter id" data-related="blogapp">Tweet</a>
            <script>!function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
            if (!d.getElementById(id)) {
                js = d.createElement(s);
                js.id = id;
                js.src = p + '://platform.twitter.com/widgets.js';
                fjs.parentNode.insertBefore(js, fjs);
            }
        }(document, 'script', 'twitter-wjs');</script>


            <script type="text/javascript" src="//opensharecount.com/bubble.js"></script>
            <div style="margin-bottom:5px"><a href="http://leadstories.com/opensharecount" target="_blank" class="osc-counter" data-dir="vertical" data-size="large" data-width="76px" data-url="https://dev.twitter.com/rest/collections" title="Powered by Lead Stories' OpenShareCount"></a></div>

            <!-- Place this tag in your head or just before your close body tag. -->
            <script src="https://apis.google.com/js/platform.js" async defer></script>

            <!-- Place this tag where you want the share button to render. -->
            <div class="g-plus" data-action="share" data-width="120" data-href="<?php echo $link; ?>"></div>

            <!-- Place this tag after the last share tag. -->
            <script type="text/javascript">
                (function() {
                    var po = document.createElement('script');
                    po.type = 'text/javascript';
                    po.async = true;
                    po.src = 'https://apis.google.com/js/platform.js';
                    var s = document.getElementsByTagName('script')[0];
                    s.parentNode.insertBefore(po, s);
                })();
            </script>

        </li>


        @endif

        <li>
            <div class="fb-comments" data-href="<?php echo $link; ?>" data-width="800" data-numposts="5"></div>

        </li>

        <li>
            <div class="fb-like" data-share="true" data-width="450" data-show-faces="true"></div>
        </li>
        <li>
            <div id="twitterCount">
            </div>

        </li>

    </ul>
    @endif
</div>
@else
404 error
@endif
@endsection
<script>
    function share(id) {
        var x = document.getElementById('image' + id).getAttribute('src');
        //alert(x);
        window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(x), 'facebook-share-dialog', 'width=626,height=436');
        return false;
    }

</script>
