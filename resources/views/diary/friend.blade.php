@extends('layout')
@section('title', '友達ページ')
<header class="header">
  <ul class="header-lists">
    <li class="header-list">
      <a href="{{ route('home') }}"><i class="fa-solid fa-chevron-left"></i></a>
    </li>
    <li class="header-list">
      {{ $friend->username }}
    </li>
    <li class="header-list">
      
    </li>
  </ul>
</header>
<div class="inner">
  <main>
    <div class="friend-profile">
      <div class="friend-img">
        <img src="{{ $friend->profile_pic ? asset('storage/profile_pics/' . $friend->profile_pic) : asset('img/default_profile_pic.jpg') }}" alt="">
      </div>
      <div class="friend-name-container">
        <h1 class="friend-name">{{ $friend->name }}</h1>
      </div>
    </div>

    @foreach ($recentMemories as $memory)
      <div class="friend-diary">
        <div class="diary-img">
          <img src="{{ asset('storage/' . $memory->file_path) }}" alt="User memory image" />
          <div id="like-app-{{ $memory->id }}">
            <like-button :memory="{{ $memory }}"></like-button>
          </div>
        </div>
        <p class="diary-caption">{{ $memory->caption }}</p>
        <p class="diary-entry">{{ $memory->entry }}</p>
      </div>
    @endforeach
  </main>
</div>