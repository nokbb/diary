@extends('layout')
@section('title', '友達検索')
<header class="header">
  <ul class="header-lists">
    <li class="header-list">
      <a href="{{ route('home') }}"><i class="fa-solid fa-chevron-left"></i></a>
    </li>
    <li class="header-list">
      <a href="{{ route('home') }}">diary.</a>
    </li>
    <li class="header-list">
      
    </li>
  </ul>
</header>
<div class="inner">
  <main>
    <div class="inner">
        <form action="{{ route('search.results') }}" method="GET">
          <div class="search-form">
            <input type="text" name="query" placeholder="Search" class="search-box">
            <button type="submit" class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
          </div>
        </form>

        @if(isset($results))
          @if($results->isEmpty())
            <p>ユーザーが存在しません。</p>
          @else
            @foreach($results as $user)
              <div class="search-result">
                <a href="{{ route('friend.show', ['friendId' => $user->id]) }}" @click="setFriendId({{ $user->id }})" class="search-result-container">
                  <div class="friend-icon">
                    <img src="{{ $user->profile_pic ? asset('storage/profile_pics/' . $user->profile_pic) : asset('img/default_profile_pic.jpg') }}" alt="友達のアイコン">
                  </div>
                  <div class="search-friend-name">
                    <p>{{ $user->name }}</p>
                  </div>
                </a>
                <form action="{{ route('friend.add') }}" method="POST">
                  @csrf
                  <div class="search-addition-btn">
                    <input type="hidden" name="friend_id" value="">
                    <div id="app">
                      <friend-addition :friend-id="{{ $user->id }}"></friend-addition>
                    </div>
                  </div>
                </form>
              </div>
            @endforeach
          @endif
        @else
          <p>検索結果はありません。</p>
        @endif
      </div>
  </main>
</div>