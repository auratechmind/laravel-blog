<?php namespace App\Modules\Blog;
use Illuminate\Routing\Router;
//use App\Providers\ModuleRouteServiceProvider as ServiceProvider;
class BlogServiceProvider extends \App\Modules\ModulesServiceProvider {

    public function register()
    {
        parent::register('Blog');
    }

    public function boot()
    {
        parent::boot('Blog');
    }


    
}