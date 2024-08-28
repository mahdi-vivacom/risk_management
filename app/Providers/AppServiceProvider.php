<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Spatie\Permission\Models\Permission;


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
        Event::listen(Authenticated::class, function ($event) {

            view()->composer('*', function ($view) {

                $userPermissions = Auth::check() ? (Auth::user()->hasRole('systemadmin') ? Permission::all()->pluck('id')->toArray() : Auth::user()->getAllPermissions()->pluck('id')->toArray()) : [];
                $menuData = Menu::orderBy('serial', 'asc')
                    ->where(function ($query) use ($userPermissions) {
                        $query->whereNull('parent_id')
                            ->whereIn('permission_id', $userPermissions)
                            ->orderBy('serial', 'asc')
                            ->orWhereHas('SubMenu', function ($subQuery) use ($userPermissions) {
                                $subQuery->whereIn('permission_id', $userPermissions);
                            });
                    })
                    ->get();

                $view->with([
                    'userPermissions' => $userPermissions,
                    'menuData' => $menuData,
                ]);
            });

        });
    }
}
