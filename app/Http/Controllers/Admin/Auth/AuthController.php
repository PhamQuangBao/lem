<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getlogin()
    {
        // $user = new \App\Models\User;
        // $user->name = 'admin';
        // $user->email = 'bao.pham@gmail.com';
        // $user->password = \Hash::make('123456');
        // $user->save();
        return view('admin/users/login', [
            'title' => 'Login LEM',
        ]);
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email:filter',
            'password' => 'required'
        ]);

        if (Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            // 'level' => 1
        ], $request->input('remember'))) {
            return redirect('/');
        } else {
            return redirect('/login')->with('error', 'Email or password wrong!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    //Google login
    public function redirectGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
}
