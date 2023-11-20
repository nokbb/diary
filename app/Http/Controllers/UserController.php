<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;

class UserController extends Controller
{
    /**
     * 新規登録画面を表示する
     * 
     * @return view
     */
    public function showRegister()
    {
        return view('diary.register');
    }

    /**
     * 新規登録
     * 
     * @return view
     */
    public function register(RegisterRequest $request)
    {
        $user = User::registerUser($request->validated());

        Auth::login($user);

        return redirect()->route('home')->with('success', '登録完了しました！');
    }
}
