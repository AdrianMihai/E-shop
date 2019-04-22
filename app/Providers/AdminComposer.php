<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;
use DB;

class AdminComposer extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $pages = ['admin_layouts.dashboard', 'admin_layouts.products', 'admin_layouts.employees', 'admin_layouts.orders',
                  'admin_layouts.logs', 'admin_layouts.settings'];
        view()->composer($pages, function($view){
            $admin_data = ['id' => Auth::guard('admin')->user()->id,
                           'first_name' => Auth::guard('admin')->user()->first_name,
                           'last_name' => Auth::guard('admin')->user()->last_name,
                           'email' => Auth::guard('admin')->user()->email,
                           'position' => Auth::guard('admin')->user()->position,
                           'image_path' => Auth::guard('admin')->user()->image_path,
                           'categories' => DB::table('categories')->orderBy('category', 'asc')->pluck('category'),
                           ];
            $view->with($admin_data);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
