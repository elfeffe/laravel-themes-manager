<?php

declare(strict_types=1);

namespace Hexadog\ThemesManager\Http\Middleware;

use Hexadog\ThemesManager\Facades\ThemesManager;
use Illuminate\Http\Request;

class ThemeLoader
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, \Closure $next, ?string $theme = null)
    {
        // Do not load theme if the App is running in console. The Accept header
        // is not a reliable signal: a client that asks for JSON can still hit a
        // route that renders theme views, and skipping the theme leaves them
        // unresolvable ("View [layouts.app] not found").
        if (app()->runningInConsole()) {
            return $next($request);
        }

        if (! is_null($theme)) {
            ThemesManager::set($theme);
        } else {
            if ($theme = config('themes-manager.fallback_theme')) {
                ThemesManager::set($theme);
            }
        }

        return $next($request);
    }
}
