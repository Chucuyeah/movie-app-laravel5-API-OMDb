<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        if ($username === "aldmic" && $password === "123abc123") {
            session(['user' => $username]);
            session()->save(); // Force save session
            return redirect('/movies')->with('success', 'Login berhasil!');
        }

        return redirect('/login')->with('error', 'Username atau Password salah!');
    }

    public function logout()
    {
        session()->forget('user');
        return redirect('/login');
    }

    public function setLanguage($locale)
    {
        // Validate locale
        if (!in_array($locale, ['en', 'id'])) {
            $locale = 'id';
        }

        session(['locale' => $locale]);
        session()->save();

        return redirect()->back();
    }
}
