<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Per-request query profiler. Logs `URL | queries | total_time` plus the
 * top 5 slowest queries to laravel.log. Static state is required because
 * Laravel resolves the middleware as a different instance for handle() vs
 * terminate(), so capturing $this->queries breaks silently.
 * Disabled in production via APP_ENV check. Zero overhead when off.
 */
class ProfileQueries
{
    private static array $queries = [];
    private static float $start = 0.0;
    private static bool $listening = false;

    public function handle(Request $request, Closure $next)
    {
        if (!config('app.debug')) {
            return $next($request);
        }

        self::$queries = [];
        self::$start = microtime(true);

        if (!self::$listening) {
            DB::listen(function ($q) {
                self::$queries[] = ['sql' => $q->sql, 'time' => $q->time];
            });
            self::$listening = true;
        }

        return $next($request);
    }

    public function terminate(Request $request, $response): void
    {
        if (!config('app.debug')) {
            return;
        }

        $total = round((microtime(true) - self::$start) * 1000);
        $count = count(self::$queries);
        $sumQueryMs = array_sum(array_column(self::$queries, 'time'));

        $sorted = self::$queries;
        usort($sorted, fn ($a, $b) => $b['time'] <=> $a['time']);
        $top5 = array_slice($sorted, 0, 5);

        $top5Out = array_map(function ($q) {
            return sprintf('    %sms | %s', round($q['time']), substr(preg_replace('/\s+/', ' ', $q['sql']), 0, 180));
        }, $top5);

        Log::info(sprintf(
            "[PROFILE] %s %s — queries=%d req_total=%sms in_queries=%sms\n%s",
            $request->method(),
            $request->fullUrl(),
            $count,
            $total,
            round($sumQueryMs),
            implode("\n", $top5Out)
        ));
    }
}
