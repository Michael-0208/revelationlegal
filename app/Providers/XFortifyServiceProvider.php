<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Providers\FortifyServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest;
use App\Models\User;
use Hash;

class XFortifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::authenticateUsing(function (LoginRequest $request) {
            dd("HERE");
            $user = User::where('email', $request->username)
                ->orWhere('username', $request->username)->first();
            if ($user &&
                Hash::check($request->password, $user->password)
            ) {
                return $user;
            }
        });
    }
}
