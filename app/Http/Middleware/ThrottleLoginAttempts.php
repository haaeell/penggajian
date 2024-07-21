<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ThrottleLoginAttempts
{
    protected $maxAttempts = 3;
    protected $lockoutTime = 180; // in seconds, 3 minutes

    public function handle($request, Closure $next)
    {
        $email = $request->input('email');
        $key = 'login_attempts_' . $email;

        if (Cache::has($key)) {
            $attempts = Cache::get($key);
            $remainingTime = Cache::get($key . '_timer');
            if ($attempts >= $this->maxAttempts) {
                $remainingTime = Cache::get($key . '_timer');
                return response()->json(['message' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $remainingTime . ' detik.'], 429);
            }
        }

        return $next($request);
    }

    public function terminate($request, $response)
    {
        if ($response->getStatusCode() == 401) {
            $email = $request->input('email');
            $key = 'login_attempts_' . $email;

            $attempts = Cache::get($key, 0) + 1;
            Cache::put($key, $attempts, $this->lockoutTime);

            if ($attempts >= $this->maxAttempts) {
                Cache::put($key . '_timer', $this->lockoutTime, $this->lockoutTime);
                Log::warning("User locked out after too many login attempts: $email");
            }
        }
    }
}
