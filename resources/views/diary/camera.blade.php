@extends('layout')
@section('title', 'カメラ')
<header class="header">
  <ul class="header-lists">
    <li class="header-list close-camera-btn">
      <input
      type="button"
      value=""
      onclick="closeCamera()"
      class="close-camera"
    />
    <span class="close-camera-icon"
      ><i class="fa-solid fa-chevron-down"></i
    ></span>
    </li>
    <li class="header-list">
      <a href=""><i class="fa-solid fa-circle-user"></i></a>
    </li>
    <li class="header-list">
      
    </li>
  </ul>
</header>
<div class="inner">
  <main>
    <!-- <div class="camera-container">
      <div class="camera-btn">
        <input
          type="button"
          onclick="cameraInitSmartphoneSupport()"
          id="camera-action-btn"
        />
      </div>
      <div class="camera-canvas">
        <video id="camera" autoplay muted playsinline></video>
        <canvas id="canvas" crossorigin="anonymous"></canvas>
        <button id="clear-canvas-btn" style="display: none">×</button>
      </div>
    </div>
    <form action="" method="">
      <div class="text-container">
        <div class="textarea-wrap">
          <textarea
            class="auto_resize"
            name=""
            id=""
            cols="30"
            rows="1"
            placeholder="キャプション"
          ></textarea>
          <div class="underline-container">
            <div class="underline" style="top: 2rem"></div>
          </div>
        </div>
        <div class="textarea-wrap">
          <textarea
            class="auto_resize"
            name=""
            id=""
            cols="30"
            rows="3"
            placeholder="本文"
          ></textarea>
          <div class="underline-container">
            <div class="underline" style="top: 2rem"></div>
          </div>
        </div>
      </div>
      <div class="addition-container">
        <ul class="addition-lists">
          <li class="addition-list">
            <input type="radio" name="category" value="○" id="category1" />
            <label for="category1">○</label>
          </li>
          <li class="addition-list">
            <input type="radio" name="category" value="△" id="category2" />
            <label for="category2">△</label>
          </li>
          <li class="addition-list">
            <input type="radio" name="category" value="□" id="category3" />
            <label for="category3">□</label>
          </li>
        </ul>
        <button type="submit" class="addition-btn">追加</button>
      </div>
    </form> -->
  </main>
</div>