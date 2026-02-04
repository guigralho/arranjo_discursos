<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockSuspiciousRequests
{
    public function handle(Request $request, Closure $next)
    {
        // Lista de padrões suspeitos
        $suspiciousPatterns = [
            's=captcha',
            's=index',
            'wp-admin',
            'wp-login',
            'xmlrpc',
            '.env',
            'phpinfo',
        ];

        $uri = $request->getRequestUri();

        foreach ($suspiciousPatterns as $pattern) {
            if (stripos($uri, $pattern) !== false) {
                abort(403, 'Forbidden');
            }
        }

        return $next($request);
    }
}
