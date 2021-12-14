<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function loginPage()
    {
        return view('auth.login')->with('title','User Login');
    }

    public function login(Request $request)
    {

        $credentials = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
            // return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);


        return view('auth.login')->with('title','User Login');
    }

    public function registerPage()
    {
        return view('auth.register')->with('title','User Registration');
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'email|required|unique:users,email',
            'password' => 'required|min:6',
            'name' => 'required|min:2',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $dataSave = $user->save();

        if ($dataSave) {
            if (Auth::loginUsingId($user->id)) {
                $request->session()->regenerate();

                return redirect()->intended('dashboard');
            }
        }else{
            return redirect()->back()->with('fail','something went wrong');
        }

        return view('auth.login')->with('title','User Login');
    }
}
