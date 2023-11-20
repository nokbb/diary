@extends('layout')
@section('title', 'パスワードリセットフォーム')
<div class="login-inner">
  <form action="{{ route('send') }}" method="POST" class="login">
    @csrf
    <fieldset>
      <legend class="login-head">Password reset</legend>
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
      <div class="login-wrapper">
        <button type="submit" class="login-btn">送 信</button>
      </div>
      <a href="{{ ('/') }}" class="to-login">ログイン画面へ</a>
    </fieldset>
  </form>
</div>