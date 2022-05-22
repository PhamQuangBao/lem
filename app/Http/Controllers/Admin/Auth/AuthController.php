<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAuthRequest;
use App\Models\Users;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * @var UserRepositoryInterface|\App\Repositories\Repository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //Auth::logout();
        $auth = Auth::user();
       // dd($auth);
        return view('admin.auth.edit', [
            'title' => 'Edit profile',
            'auth' => $auth,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\UpdateAuthRequestRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAuthRequest $request)
    {
        $data = $request->all();
        //check have password and have checkbox
        if(isset($data['password']) && $request->editPassword == 'on') {
            $data = $request->only(['name','password']);
            $data['password'] = bcrypt($data['password']);
        } else {
            $data = $request->only(['name']);
        }
        $userID = Auth::user()->id;
        $auth = $this->userRepository->find($userID);
        if($auth){
            $auth_detail = $this->userRepository->update($userID, $data);
            if ($auth_detail) {
                return redirect()->back()->with('success', 'Update user detail Success!');
            } else {
                return redirect()->back()->with('error', 'Update user detail has something wrong!!');
            }
        }
        else{
            return redirect()->back()->with('error', 'user not found!');
        }    
    }
}
