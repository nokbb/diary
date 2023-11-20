@extends('layout')
@section('title', 'マイページ編集')
<header class="header">
  <ul class="header-lists">
    <li class="header-list">
      <a href="javascript:history.back()"><i class="fa-solid fa-chevron-left"></i></a>
    </li>
    <li class="header-list">
      プロフィール編集
    </li>
    <li class="header-list memory-meatball-menu">
      
    </li>
  </ul>
</header>
<main>
  <form action="{{ route('mypageUpdate') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="myPage">
      <div class="myPage-img">
        <img src="{{ $user->profile_pic ? asset('storage/profile_pics/' . $user->profile_pic) : asset('img/default_profile_pic.jpg') }}" alt="myPage" />
      </div>
      <div class="mypage-container">
        <input type="file" id="profile_pic" name="profile_pic">
        @if($user->profile_pic)
          <button type="button" id="deleteProfileImageBtn">プロフィール画像を削除</button>
        @endif
      </div>
    </div>
    <div class="mypage-edit-container">
      <div class="myName-container">
        <div class="myName-wrap">
          <label for="myName">名前</label>
          <input type="text" id="myName" name="name" value="{{ $user->name }}" />
        </div>
      </div>
      <div class="myUserName-container">
        <div class="myUserName-wrap">
          <label for="myUserName">ユーザー名</label>
          <input type="text" id="myUserName" name="username" value="{{ $user->username }}" />
        </div>
      </div>
    </div>
    <div class="update-btn-container">
      <button class="update-btn">更新</button>
    </div>
  </form>
</main>