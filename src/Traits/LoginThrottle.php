<?php

namespace Jaffran\LaravelTools\Traits;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

trait LoginThrottle
{
    public function ensureNotRateLimited()
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), config('LaravelTools.max_login_attempt'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.throttle', ['seconds' => RateLimiter::availableIn($this->throttleKey())]),
            ]);
        }
        RateLimiter::hit($this->throttleKey());
    }
}
