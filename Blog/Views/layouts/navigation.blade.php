<nav class="navbar navbar-default">
        <div class="container-fluid">
                <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                <span class="sr-only">Toggle Navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="{{ url('/') }}">My Blog</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                                <li>
                                        <a href="{{ url('/') }}">Home</a>
                                </li>
                        </ul>

                        <ul class="nav navbar-nav navbar-right">
                                @if (Auth::guest())
                                <li>
                                        <a href="{{ url('/auth/login') }}">Login</a>
                                </li>
                                <li>
                                        <a href="{{ url('/auth/register') }}">Register</a>
                                </li>
                                @else

<?php
$variable = App\Modules\Blog\Components\GeneralFunctions::getCategoryMenu();
if(isset($variable)) { ?>

                                 <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Category<span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                         
                                        <?php foreach ($variable as $v){
                                       ?>
                                        <li>
												<a href="{{ url('/categorywise') }}<?php echo "/".$v->category_name;?>"><?php echo $v->category_name;?></a>
										</li>
                                       <?php
                                        }
                                        ?>
                                        </ul>
                                </li>

<?php } ?>
                                
                                <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
												@if (Auth::user()->is_admin())

                                                 <li>
                                                        <a href="{{ url('/admin/category') }}">Manage Category</a>
                                                </li>
												@endif
												@if (Auth::user()->can_post())
												<li>
														<a href="{{ url('/new-post') }}">Add new post</a>
												</li>
												<li>
														<a href="{{ url('/user/'.Auth::id().'/posts') }}">My Posts</a>
												</li>
												@endif
                                                <li>
                                                        <a href="{{ url('/user/'.Auth::id()) }}">My Profile</a>
                                                </li>
                                                <li>
                                                        <a href="{{ url('/auth/logout') }}">Logout</a>
                                                </li>
                                        </ul>
                                </li>
                                @endif
                        </ul>
                </div>
        </div>
</nav>
