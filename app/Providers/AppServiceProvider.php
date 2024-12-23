<?php

namespace App\Providers;

use App\Models\Attendee;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
        Gate::define('update', function (User $user, Event $event) {
            return $user->id === $event->user_id;
        });

        Gate::define('delete', function (User $user, Event $event, Attendee $attendee) {
            return $user->id === $attendee->user_id || $user->id === $event->user_id;
        }); */
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
	            $request->user()?->id ?: $request->ip()
	          );
        });
    }
}
