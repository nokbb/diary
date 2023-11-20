@extends('layout')
@section('title', 'リセットメール送信完了')
<div class="login-inner">
  <div class="complete-container">
    <p class="complete-text">送信が完了しました。</p>
    <a href="{{ route('showLogin') }}" class="to-login">ログイン画面へ</a>
  </div>
</div>