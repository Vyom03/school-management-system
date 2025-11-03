<?php

namespace App\Http\Controllers\Parent\Auth;

use App\Http\Controllers\Controller;
use App\Models\ParentUser;
use App\Models\ParentRegistrationCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class ParentRegisteredUserController extends Controller
{
    /**
     * Display the parent registration view.
     */
    public function create(): View
    {
        return view('parent.auth.register');
    }

    /**
     * Handle an incoming parent registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:parent_users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'registration_code' => ['required', 'string', 'size:8'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        // Verify registration code
        $code = ParentRegistrationCode::where('code', strtoupper($request->registration_code))->first();

        if (!$code) {
            return back()->withErrors([
                'registration_code' => 'Invalid registration code. Please check and try again.',
            ])->withInput();
        }

        if (!$code->isValid()) {
            if ($code->used) {
                return back()->withErrors([
                    'registration_code' => 'This registration code has already been used.',
                ])->withInput();
            }
            
            if ($code->expires_at && $code->expires_at->isPast()) {
                return back()->withErrors([
                    'registration_code' => 'This registration code has expired.',
                ])->withInput();
            }
        }

        // If email is pre-approved, verify it matches
        if ($code->email && strtolower($code->email) !== strtolower($request->email)) {
            return back()->withErrors([
                'email' => 'The email address does not match the one associated with this registration code.',
            ])->withInput();
        }

        // Create parent account
        $parent = ParentUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Link parent to student
        $parent->students()->attach($code->student_id, [
            'relationship' => $code->relationship,
            'is_primary' => true, // First parent using the code is primary
        ]);

        // Mark code as used
        $code->markAsUsed();

        // Auto-login the parent
        Auth::guard('parent')->login($parent);

        return redirect()->route('parent.dashboard');
    }
}
