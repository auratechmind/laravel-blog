<!DOCTYPE html>
<html lang="en">
	@include('Blog::layouts.header')
	<body>
		<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : 'your app id',
      xfbml      : true,
      version    : 'v2.6'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
   
</script>

  
          @include('Blog::layouts.navigation')
          <div class="container">
			@if (Session::has('message'))
			<div class="flash alert-info">
				<p class="panel-body">
					{{ Session::get('message') }}
				</p>
			</div>
			@endif
	<!--		@if ($errors->any())
			<div class='flash alert-danger'>
				<ul class="panel-body">
					@foreach ( $errors->all() as $error )
					<li>
						{{ $error }}
					</li>
					@endforeach
				</ul>
			</div>
			@endif-->
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h2>@yield('title')</h2>
							@yield('title-meta')
						</div>
						<div class="panel-body">
							@yield('content')
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<p>Copyright &copy; 2016 | <a href="{{ url('/') }}">My Blog</a></p>
				</div>
			</div>
		</div>

		<!-- Scripts -->
		<!--<script src="{{ asset('/js/jquery.min-2.1.3.js') }}"></script>-->
		<script src="{{ url('../app/Modules/Blog/Assets/js/bootstrap.js') }}"></script>  <!-- {{ asset('/js/bootstrap.min-3.3.1.js') }} -->
		
	</body>
</html>
