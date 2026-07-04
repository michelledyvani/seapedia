<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckActiveRole
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $activeRole = session('active_role');

        if (is_null($activeRole)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No active role selected.'], 403);
            }
            return redirect()->route('role.select');
        }

        if ($activeRole !== $role) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. Active role mismatch.'], 403);
            }
            abort(403, 'Akses ditolak. Role aktifmu bukan ' . $role . '.');
        }

        return $next($request);
    }
}
