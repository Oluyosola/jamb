<?php

namespace App\Http\Middleware;

use App\Enums\EventType;
use Closure;
use Illuminate\Http\Request;

class LogsActivity
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
        if (auth()->check()) {
            activity()
            ->event(EventType::VISITED)
            ->log("Authenticated User visited {$request->fullUrl()}");
        }
        return $next($request);
    }
}
