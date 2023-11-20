@extends('layout')
@section('title', 'パスワードリセット再設定完了')
<div class="login-inner">
  <div class="complete-container">
    <p class="complete-text">登録が完了しました。</p>
    <a href="{{ route('showLogin') }}" class="to-login">ログイン画面へ</a>
  </div>
</div>