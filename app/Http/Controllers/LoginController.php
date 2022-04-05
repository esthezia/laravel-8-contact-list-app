<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;

class LoginController extends Controller
{
    public function index(Request $request) {
        if (Auth::check()) {
            // user already logged in
            return redirect('/');
        }

        if ($request->isMethod('post')) {
            $credentials = $request->only('email', 'password');

            $validator = Validator::make($credentials, [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails() || ($incorrectCredentials = !Auth::attempt($credentials))) {
                if ($incorrectCredentials) {
                    Session::flash('login-error', 'The credentials are not valid.');
                }

                return redirect('login')->withErrors($validator);
            }

            return redirect('admin');
        }

        return view('login');
    }

    public function logout() {
        Session::flush();
        Auth::logout();

        return Redirect('/');
    }
}
