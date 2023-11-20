@extends('layout')
@section('title', 'ホーム')
<header class="header">
  <ul class="header-lists">
    <li class="header-list">
      <a href="{{ route('friend.search') }}"><i class="fa-solid fa-magnifying-glass"></i></a>
    </li>
    <li class="header-list">
      diary.
    </li>
    <li class="header-list">
      <a href="{{ route('mypage') }}">
        @if(Auth::user()->profile_pic)
          <img src="{{ asset('storage/profile_pics/' . Auth::user()->profile_pic) }}" alt="User Profile Picture" style="width: 40px; height: 40px; border-radius: 50%;">
        @else
          <i class="fa-solid fa-circle-user"></i>
        @endif
      </a>
    </li>
  </ul>
</header>
<div class="inner">
    <!-- <x-alert type="success" :session="session('success')"/> -->
    <!-- @if (Auth::check())
    <ul>
      <li>
        名前：{{ Auth::user()->name }}
      </li>
      <li>
        メールアドレス：{{ Auth::user()->email }}
      </li>
    </ul>
    @endif -->
  <main>
    @if (Auth::check())
      <div id="app">
        <upload-component></upload-component>
      </div>
    @endif
    
    @if($memory)
      <div class="my-diary">
        <div class="diary-img">
          <img src="{{ asset('storage/' . $memory->file_path) }}" alt="User memory image" />
          <div class="like-profilePics">
            @if(isset($memory->likeStatus['profile_pics']))
                @foreach($memory->likeStatus['profile_pics'] as $pic)
                    <div class="like-profilePic">
                        @if($pic)
                            <img src="{{ asset('storage/profile_pics/' . $pic) }}" alt="Profile Picture">
                        @else
                            <i class="fa-solid fa-circle-user"></i> <!-- デフォルトアイコン -->
                        @endif
                    </div>
                @endforeach
            @endif
          </div>
        </div>
        <p class="diary-caption">{{ $memory->caption }}</p>
        <p class="diary-entry">{{ $memory->entry }}</p>
      </div>
    @endif
    
    @foreach ($friendMemories as $memory)
      <a href="{{ route('friend.show', ['friendId' => $memory->user_id]) }}" class="friend-diary">
        <div class="user-container">
          <!-- ユーザー情報の表示は必要に応じて調整 -->
          @if($memory->user->profile_pic)
            <div class="user-icon">
              <img src="{{ asset('storage/profile_pics/' . $memory->user->profile_pic) }}" alt="User Profile Picture">
            </div>
          @else
            <!-- プロフィール画像がない場合はデフォルトのアイコンを表示 -->
            <div class="user-icon"><i class="fa-solid fa-circle-user"></i></div>
          @endif
          <p class="user-name">{{ $memory->user->username }}</p>
          <time datetime="{{ $memory->created_at }}">{{ $memory->created_at }}</time>
        </div>
      </a>
      <div class="diary-img">
        <img src="{{ asset('storage/' . $memory->file_path) }}" alt="User memory image" />
        <div id="like-app-{{ $memory->id }}">
          <like-button :memory="{{ $memory }}"></like-button>
        </div>
      </div>
        <p class="diary-caption">{{ $memory->caption }}</p>
        <p class="diary-entry">{{ $memory->entry }}</p>
    @endforeach
  </main>
</div>
