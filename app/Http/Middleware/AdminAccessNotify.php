<?php

namespace App\Http\Middleware;

use App\Services\TelegramService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AdminAccessNotify
{
    /**
     * Handle an incoming request.
     * Send a Telegram alert when an admin dashboard access is requested.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $service = new TelegramService();

        // Determine client IP (prefer first X-Forwarded-For if present)
        $xff = (string) $request->headers->get('x-forwarded-for');
        $forwardedIp = $xff ? trim(Str::of($xff)->explode(',')->first()) : null;
        $clientIp = $forwardedIp ?: $request->ip();

        // Build a per-IP per-day cache key
        $date = now()->toDateString();
        $cacheKey = "admin_access_notify:{$clientIp}:{$date}";

        if (!Cache::has($cacheKey)) {
            $title = '<b>📥 Yêu cầu truy cập Admin Dashboard</b>';
            $lines = [];
            $lines[] = $title;
            $lines[] = '';
            $lines[] = '<b>URL:</b> ' . htmlspecialchars($request->fullUrl());
            $lines[] = '<b>Method:</b> ' . htmlspecialchars($request->method());
            $lines[] = '<b>IP:</b> ' . htmlspecialchars($clientIp);
            if ($xff) { $lines[] = '<b>X-Forwarded-For:</b> ' . htmlspecialchars($xff); }
            $ua = $request->userAgent();
            if ($ua) { $lines[] = '<b>User-Agent:</b> <pre>' . htmlspecialchars($ua) . '</pre>'; }
            $ref = $request->headers->get('referer');
            if ($ref) { $lines[] = '<b>Referer:</b> ' . htmlspecialchars($ref); }
            $lines[] = '<b>Thời gian:</b> ' . now()->format('Y-m-d H:i:s');

            $message = implode("\n", $lines);

            try {
                $service->sendMessage($message);
                // Mark as notified until end of day
                Cache::put($cacheKey, true, now()->endOfDay());
            } catch (\Throwable $e) {
                // Do not break the request flow; log at warning to avoid telegram log loop
                Log::warning('AdminAccessNotify telegram failed: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}
