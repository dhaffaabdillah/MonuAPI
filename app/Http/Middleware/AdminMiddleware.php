<?php

namespace App\Http\Middleware;

use App\Http\Library\apiHelper;
use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    use apiHelper;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $role = $request->user()->role;

        if ($role !== 3) {
            return $this->onError(403, 'Access not allowed');
        }
        return $next($request);
    }
}
