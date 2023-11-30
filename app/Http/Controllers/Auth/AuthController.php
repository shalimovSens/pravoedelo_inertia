<?php

namespace App\Http\Controllers\Auth;


use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Login as LoginRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth');
    }
    /**
     * залогинится
     */
    public function store(LoginRequest $request) : RedirectResponse
    {
        $request->authenticate();
        if($request->user()->roles->first()->name == 'Client') {
            $this->logout($request);
        }
        $request->session()->regenerate();
        return redirect()->route('table');
    }
    /**
     * разлогинится
     */
    public function logout(Request $request) : RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
    

