@extends('layout')
@section('title', 'マイページ')
<header class="header">
  <ul class="header-lists">
    <li class="header-list">
      <a href="{{ route('home') }}"><i class="fa-solid fa-chevron-left"></i></a>
    </li>
    <li class="header-list">
      プロフィール
    </li>
    <li class="header-list memory-meatball-menu">
      <i class="fa-solid fa-ellipsis"></i>
    </li>
  </ul>
</header>
<main>
  <div class="modal-container">
    @if(Auth::check())
      <!-- ログインしている場合の表示 -->
      <div class="modal-body">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <div class="edit-menu">
            <input type="submit" id="logout" name="logout" value="ログアウト" />
            <label for="logout"><i class="fa-solid fa-right-to-bracket"></i
              ></label>
          </div>
        </form>
          <div class="edit-menu">
            <a href="{{ route('mypageEdit') }}" class="login-menu">
              <p class="menu-text">編集</p>
              <span class="menu-icon"
                ><i class="fa-solid fa-pen-to-square"></i></span>
            </a>
          </div>
      </div>
    @else
      <!-- ログインしていない場合の処理 -->
      <div class="modal-body" id="login">
        <a href="{{ route('showLogin') }}" class="login-menu">
          <p class="menu-text">ログイン</p>
          <span class="menu-icon"
            ><i class="fa-solid fa-right-to-bracket"></i
          ></span>
        </a>
        <a href="{{ route('showRegister') }}" class="signup-menu">
          <p class="menu-text">新規登録</p>
          <span class="menu-icon"
            ><i class="fa-solid fa-circle-plus"></i
          ></span>
        </a>
      </div>
      @endif
  </div>
  <div class="mypage-container">
    <div class="myPage-img">
      <a href="{{ route('mypageEdit') }}" class="myPage">
        <img src="{{ $user->profile_pic ? asset('storage/profile_pics/' . $user->profile_pic) : asset('img/default_profile_pic.jpg') }}" alt="myPage" />
      </a>
    </div>
    <h2 class="myName">{{ $user->name }}</h2>
    <h3 class="myUserName">{{ $user->username }}</h3>
  </div>
  <div class="inner">
    <div class="memories-container">
      <div id="app">
          @if(Auth::check())
        <category-tab></category-tab>
          @endif
        <full-calendar :mypage-view="true" :memories="{{ json_encode($memories) }}"></full-calendar>
      </div>
      <div class="seeAll-container">
        <a href="{{ route('memories') }}" class="seeAll-btn">全てのメモリーを見る</a>
      </div>
    </div>
  </div>
</main>