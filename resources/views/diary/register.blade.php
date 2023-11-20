@extends('layout')
@section('title', '新規登録')
<div class="login-inner">
  <form action="{{ route('register') }}" method="POST" class="login">
    @csrf
    <fieldset>
      <legend class="login-head">Register</legend>
      @foreach ($errors->all() as $error)
        <ul class="alert alert-danger">
          <li>{{ $error }}</li>
        </ul>
      @endforeach

      <x-alert type="danger" :session="session('danger')"/>

      <div class="input">
        <input type="name" name="name" placeholder="Name" required>
        <span class="fade"><i class="fa-solid fa-face-smile"></i></span>
      </div>
      <div class="input">
        <input type="name" name="username" placeholder="UserName" required>
        <span class="fade"><i class="fa-solid fa-user"></i></span>
      </div>
      <div class="input">
        <input type="email" name="email" placeholder="Email" required>
        <span class="fade"><i class="fa-solid fa-envelope"></i></span>
      </div>
      <div class="input">
        <input type="password" name="password" placeholder="Password" required>
        <span class="fade"><i class="fa-solid fa-lock"></i></span>
        <span class="eye"><img src="img/close.jpg" alt="eye"></span>
      </div>
      <div class="input">
        <input type="password" name="password_confirmation" placeholder="PasswordConfirm" required>
        <span class="fade"><i class="fa-solid fa-lock"></i></span>
        <span class="eye"><img src="img/close.jpg" alt="eye"></span>
      </div>
        <button type="submit" class="login-btn">新規登録</button>
        <a href="{{ route('showLogin') }}" class="login-screen">ログイン画面へ</a>
    </fieldset>
  </form>
</div>