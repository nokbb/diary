@extends('layout')
@section('title', 'メモリー一覧')
<header class="header">
  <ul class="header-lists">
    <li class="header-list">
      <a href="javascript:history.back()"><i class="fa-solid fa-chevron-left"></i></a>
    </li>
    <li class="header-list">
      メモリー
    </li>
    <li class="header-list memory-meatball-menu">
      
    </li>
  </ul>
</header>
<main>
  <div class="inner">
    <div class="memories-container">
      <div id="app">
        @if(Auth::check())
        <category-tab></category-tab>
          @endif
        <full-calendar :memories="{{ json_encode($memories) }}"></full-calendar>
      </div>
    </div>
    </div>
  </div>
</main>