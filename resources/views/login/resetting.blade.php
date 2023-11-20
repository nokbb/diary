@extends('layout')
@section('title', 'パスワードリセット再設定')
<div class="login-inner">
  <form action="{{ route('update') }}" method="POST" class="login">
    @csrf
    <fieldset>
      <legend class="login-head">Password resetting</legend>
      @foreach ($errors->all() as $error)
        <ul class="alert alert-danger">
          <li>{{ $error }}</li>
        </ul>
      @endforeach

      <x-alert type="danger" :session="session('danger')"/>

      <input type="hidden" name="reset_token" value="{{ $userToken->token }}">

      <div class="input">
        <input type="password" name="password" placeholder="Password" required />
        <span class="fade"><i class="fa-solid fa-lock"></i></span>
        <span class="eye"><img src="img/close.jpg" alt="eye" /></span>
      </div>
      <div class="input">
        <input type="password" name="password_confirmation" placeholder="Password" required />
        <span class="fade"><i class="fa-solid fa-lock"></i></span>
        <span class="eye"><img src="img/close.jpg" alt="eye" /></span>
      </div>
      <div class="login-wrapper">
        <button type="submit" class="login-btn">登録</button>
      </div>
      <a href="{{ route('showLogin') }}" class="to-login">ログイン画面へ</a>
    </fieldset>
  </form>
</div>