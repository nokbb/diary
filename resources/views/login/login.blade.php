@extends('layout')
@section('title', 'ログイン')
<div class="login-inner">
  <form action="{{ route('login') }}" method="POST" class="login">
    @csrf
    <fieldset>
      <legend class="login-head">Login</legend>
      @foreach ($errors->all() as $error)
        <ul class="alert alert-danger">
          <li>{{ $error }}</li>
        </ul>
      @endforeach

      <x-alert type="danger" :session="session('danger')"/>

      <div class="input">
        <input type="email" name="email" placeholder="Email" required />
        <span class="fade"><i class="fa-solid fa-envelope"></i></span>
      </div>
      <div class="input">
        <input type="password" name="password" placeholder="Password" required />
        <span class="fade"><i class="fa-solid fa-lock"></i></span>
        <span class="eye"><img src="img/close.jpg" alt="eye" /></span>
      </div>
      <div class="login-wrapper">
        <button type="submit" class="login-btn">ログイン</button>
        <a href="{{ route('form') }}" class="password-forget">パスワードをお忘れの方</a>
      </div>
      <a href="{{ route('showRegister') }}" class="newAccount-btn">新規登録</a>
      <a href="{{ route('home') }}" class="no-login">ログインせずに使う</a>
    </fieldset>
  </form>
</div>