<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class LogAfterRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        Log::stack(['requests'])->info('app.requests', ['URI' => $request->getUri(), 'METHOD' => $request->getMethod(), 'REQUEST_BODY' => $request->all()]);
        // Log::stack(['requests'])->info($e->getMessage().' in '.$e->getFile().' at Line '.$e->getLine());
        return $next($request);
    }
}
