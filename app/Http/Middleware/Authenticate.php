<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Controllers\Middleware;

//class Authenticate extends Middleware
//{
//   /* /**
//     * Handle an incoming request.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @param  \Closure  $next
//     * @param  string|null  $guard
//     * @return mixed
//     */
//    public function handle(Request $request, Closure $next, $guard = null)
//    {
//        if (Auth::guard($guard)->guest()) {
//            if ($guard == 'admin') {
//                return redirect()->route('admin.login');
//            }
//            return redirect()->route('login');
//        }
//
//        return $next($request);
//    }*/
//}
