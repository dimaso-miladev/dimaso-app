<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminIpAllowlist
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
        $raw = env('ADMIN_ALLOWED_IPS', '');
        $allowed = array_values(array_filter(array_map('trim', explode(',', $raw))));

        // Fail-closed: if allowlist is empty, deny access to avoid accidental exposure
        if (empty($allowed)) {
            abort(403, 'Forbidden');
        }

        $clientIp = $request->ip();
        \Log::info('Admin IP Allowlist check', ['client_ip' => $clientIp, 'allowed_ips' => $allowed]);
        if (!in_array($clientIp, $allowed, true)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}

