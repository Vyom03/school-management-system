<?php

namespace App\Http\Controllers\Parent\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Parent\Auth\ParentLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ParentAuthenticatedSessionController extends Controller
{
    /**
     * Display the parent login view.
     */
    public function create(): View
    {
        return view('parent.auth.login');
    }

    /**
     * Handle an incoming parent authentication request.
     */
    public function store(ParentLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended('/parent/dashboard');
    }

    /**
     * Destroy an authenticated parent session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('parent')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/parent/login');
    }
}
