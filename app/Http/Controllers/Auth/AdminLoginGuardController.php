<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminLoginGuardController extends Controller
{
    public function adminLoginPage()
    {
        return view('auth.admin_login')->with('title','Admin User Login');
    }

    public function login(Request $request)
    {

        $credentials = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $userModel = Admin::where('email', $request->input('email'))->first();

        if (!$userModel) {
            return redirect()
                ->back()
                ->withInput($request->all())
                ->with(['_status' => 'error', '_msg' => 'Unauthorized access, no account found for this email!']);
        }

        if (!Hash::check($request->input('password'), $userModel->password)) {
            return redirect()
                ->back()
                ->with(['_status' => 'error', '_msg' => 'Unauthorized access, wrong password, please try again!']);
        }

        // dd(Auth::guard('admin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password'), 'status' => 1]));
        // $request->session()->regenerate();
        $credentials =['email' => $request->input('email'), 'password' => $request->input('password')];
        if (Auth::guard('admin')->loginUsingId($userModel->id)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);


        return view('auth.login')->with('title','User Login');
    }

    // public function registerPage()
    // {
    //     return view('auth.register')->with('title','User Registration');
    // }

    // public function register(Request $request)
    // {
    //     $this->validate($request, [
    //         'email' => 'email|required|unique:users,email',
    //         'password' => 'required|min:6',
    //         'name' => 'required|min:2',
    //     ]);

    //     $user = new User();
    //     $user->name = $request->name;
    //     $user->email = $request->email;
    //     $user->password = Hash::make($request->password);
    //     $dataSave = $user->save();

    //     if ($dataSave) {
    //         if (Auth::loginUsingId($user->id)) {
    //             $request->session()->regenerate();

    //             return redirect()->intended('dashboard');
    //         }
    //     }else{
    //         return redirect()->back()->with('fail','something went wrong');
    //     }

    //     return view('auth.login')->with('title','User Login');
    // }
}
