<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockBots
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $bots = [
            'python-requests', 'curl', 'okhttp', 'fasthttp',
            'scanner', 'cypex', 'leakix'
        ];

        foreach ($bots as $bot) {
            if (stripos($request->userAgent(), $bot) !== false) {
                abort(403, "Bot blocked");
            }
        }

        return $next($request);
    }

}
