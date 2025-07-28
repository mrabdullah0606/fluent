<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            // Flash session expired message
            session()->flash('error', 'Your session has expired. Please log in again.');

            // Redirect based on the URL path
            if ($request->is('teacher/*')) {
                return route('teacher.login');
            } elseif ($request->is('student/*')) {
                return route('student.login');
            } elseif ($request->is('admin/*')) {
                return route('admin.login');
            }

            // Default fallback
            return route('login');
        }

        return null;
    }
}
