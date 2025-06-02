<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ApplyTheme
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get theme from session or config
        $theme = session('theme', config('app.theme', 'light'));
        
        // Share theme with all views
        View::share('theme', $theme);
        
        // Add theme class to the body
        $request->attributes->add(['theme' => $theme]);
        
        return $next($request);
    }
}
