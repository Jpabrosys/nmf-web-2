<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;
use App\Models\Menu;
use App\Models\Clip;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        // 1. Helper Loading (Kept from your old code)
        $helpers = app_path('Helpers/GlobalHelpers.php');
        if (file_exists($helpers)) {
            require_once $helpers;
        }

        // 2. View Composer: Injects data into 'app.blade.php' (and other views if needed)
        // We use '*' to make sure these variables are available in all views, 
        // or you can change '*' to 'app' if your layout file is literally named 'app.blade.php'.
        View::composer('*', function ($view) {
            
            // A. Global Settings (Cached for 60 mins)
            $setting = Cache::remember('global_settings', 3600, function () {
                return Setting::where('id', 1)->first();
            });

            // B. Header Menus (Cached for 60 mins)
            $menus = Cache::remember('header_menus', 3600, function () {
                return Menu::whereRelation('type', 'type', 'Header')
                    ->whereRelation('category', 'category', 'User')
                    ->where([['status', '1'], ['menu_id', 0]])
                    ->whereNotNull('sequence_id')
                    ->where('sequence_id', '!=', 0)
                    ->orderBy('sequence_id', 'asc')
                    ->get()
                    ->take(11)
                    ->toArray();
            });

            // C. Toggle/Burger Menus (Cached for 60 mins)
            $toggleMenus = Cache::remember('toggle_menus', 3600, function () {
                return Menu::whereRelation('type', 'type', 'Header')
                    ->whereRelation('category', 'category', 'User')
                    ->where([['status', '1'], ['menu_id', 0]])
                    ->whereNotNull('sequence_id')
                    ->where('sequence_id', '!=', 0)
                    ->orderBy('sequence_id', 'asc')
                    ->get();
            });

            // D. Footer Menus (Cached for 60 mins)
            $footer_menus = Cache::remember('footer_menus', 3600, function () {
                return Menu::where('menu_id', 0)
                    ->where('status', 1)
                    ->where('type_id', '1')
                    ->where('category_id', '2')
                    ->limit(8)
                    ->get();
            });

            // E. Latest Clip for Bottom Nav (Cached for 5 mins - updates faster)
            $clip = Cache::remember('latest_clip', 300, function () {
                return Clip::with('category')
                    ->where('status', 1)
                    ->latest('id')
                    ->first();
            });

            // Share variables with the view
            $view->with('setting', $setting)
                 ->with('menus', $menus)
                 ->with('toggleMenus', $toggleMenus)
                 ->with('footer_menus', $footer_menus)
                 ->with('clip', $clip);
        });
    }
}