<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Http\Request;

use Auth;

function var_dump_ret($mixed = null) {
    ob_start();
    var_dump($mixed);
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

class UserProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $pages = ['start', 'user-settings', 'checkout', 'productView', 'react-start'];
        
        view()->composer($pages, function($view){
            $user_data = ['id' => Auth::user() ? Auth::user()->id : null,
                          'username' => Auth::user() ? Auth::user()->username : null,
            ];
            $view->with($user_data);
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
