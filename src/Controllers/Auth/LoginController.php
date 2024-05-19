<?php

namespace Monsterz\Paagez\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    protected $redirectTo = '/';

    public function __construct()
    {
        $this->redirectTo = '/'.config('paagez.prefix');
    }

    public function index()
    {
        return view('paagez::auth.login');
    }

    public function login(Request $request)
    {
        $this->checkTooManyFailedAttempts();
        $this->validate($request,[
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string'
        ]);
        $user = config('paagez.models.user')::where('email',$request->email)->first();
        if(\Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]))
        {
            \Auth::login($user);
            return redirect($this->redirectTo);
        }
        RateLimiter::hit($this->throttleKey(), $seconds = 60);
        return redirect()->route('login')->withErrors([
            'email' => __('auth.failed'),
        ])->onlyInput('email');
    }

    protected function throttleKey()
    {
        return \Str::lower(request('email')) . '|' . request()->ip();
    }

    protected function checkTooManyFailedAttempts()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 6)) {
            return;
        }
        throw ValidationException::withMessages(['email' => _trans('auth.throttle',['seconds'=>60])]);
    }

    public function logout(Request $request)
    {
        if(\Auth::check()){
            
            \Auth::logout();

            $request->session()->invalidate();
        }

        return redirect()->route('index');
    }
}