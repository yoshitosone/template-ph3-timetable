<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8|confirmed',
            ],
            [
                'name.required' => '名前を入力してください',
                'email.required' => 'メールアドレスを入力してください',
                'email.email' => 'メールアドレスの形式で入力してください',
                'email.unique' => 'このメールアドレスはすでに登録されています',
                'password.required' => 'パスワードを入力してください',
                'password.min' => 'パスワードは8文字以上で入力してください',
                'password.confirmed' => 'パスワードが確認用と一致しません',
            ]
        );

        // User モデルは password を hashed で扱うので、代入するだけでハッシュになって保存される
        $user = User::create($data);
        Auth::login($user);

        return redirect('/entries');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(
            ['email' => 'required', 'password' => 'required'],
            ['email.required' => 'メールアドレスを入力してください', 'password.required' => 'パスワードを入力してください']
        );

        // メールアドレスで users の行を探し、パスワードをハッシュで比べる
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect('/entries');
        }

        return back()->withErrors(['email' => 'メールアドレスかパスワードが違います'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
