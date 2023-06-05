<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $username = $request->input('username');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));

        $insertData = [
            'user_name' => $username,
            'email' => $email,
            'password' => $password
        ];

        DB::table('users')->insert($insertData);

        $user = DB::table('users')->where('email', $email)->first();

        Session::put('user_id', $user->user_id);
        Session::put('username', $user->user_name);
        Session::put('email', $user->email);
        Session::put('logged_in', true);

        return redirect('/login');
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = DB::table('users')->where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            Session::put('user_id', $user->user_id);
            Session::put('email', $user->email);

            return redirect('/index');
        } else {
            return "Invalid email or password.";
        }
    }
    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }
}
