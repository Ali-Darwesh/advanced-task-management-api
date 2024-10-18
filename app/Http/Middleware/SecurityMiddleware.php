<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
        //remove finger from headers
        $request->header->remove('X-Powered-By');
        $request->header->remove('Server');
        $request->header->remove('x-turbo-charged-by');
        //Add security headers
        $response->header->set('X-Fram-Options', 'deny');
        $response->header->set('X-Content-Type-Options', 'nosniff');
        $response->header->set('X-Premitted-Cross-Domain-Policies', 'none');
        $response->header->set('Referrer-Policy', 'no-referrer');
        $response->header->set('Cross-Origin-Embedder-Policy', 'require-crop');
        $response->header->set('Content-Security-Policy', "default-src 'none':style-src 'self':form-action 'self");
        $response->header->set('X-XSS-Protection', '1;mode=block');

        if (config('app.env') === 'production') {
            $response->header->set('Strict-Transport-Security', 'max-age=31536000;includeSubDomains');
        }
        return $response;
    }
}
