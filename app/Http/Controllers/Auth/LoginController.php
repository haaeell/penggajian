<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Log;

use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // protected $maxAttempts = 3; // Jumlah upaya maksimal
    // protected $decayMinutes = 3;

    protected function sendFailedLoginResponse(Request $request)
    {
        $attempts = $this->limiter()->attempts($this->throttleKey($request));

        if ($attempts >= $this->maxAttempts) {
            $this->limiter()->hit($this->throttleKey($request), $this->decayMinutes * 60);

            throw ValidationException::withMessages([
                $this->username() => [trans('auth.throttle', ['seconds' => $this->limiter()->availableIn($this->throttleKey($request))])],
            ])->status(429);
        }

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    protected function redirectTo()
    {
        if (Auth::user()->role == 'karyawan') {
            return '/penggajian';
        }

        return '/home';
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
