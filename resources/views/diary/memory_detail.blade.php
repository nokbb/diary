@extends('layout')
@section('title', 'メモリー詳細')
@include('header')
<main>
  <div class="inner">
    <form action="" method="POST">
      @csrf
      @method('PUT')

      <div class="modal-container">
        <div class="modal-body">
          <div class="edit-menu">
            <button type="submit" id="edit">編集</button>
            <label for="edit">
              <i class="fa-solid fa-pen-to-square"></i>
            </label>
          </div>
        </div>
      </div>
    </form>
    <form action="" method="POST">
      @csrf
      @method('DELETE')

      <div class="modal-container">
        <div class="modal-body">
          <div class="delete-menu">
            <button type="submit" id="delete">削除</button>
            <label for="delete"><i class="fa-solid fa-trash"></i></label>
          </div>
        </div>
      </div>
    </form>
    <div class="paginetion"></div>
    <div class="detail-diary">
      <div class="detail-img">
        <img src="{{ asset($memory->file_path) }}" alt="detail-img" />
      </div>
    </div>
    <div class="detail-text-container">
      <div class="textarea-wrap">
        <textarea
          class="auto_resize"
          name=""
          id=""
          cols="30"
          rows="1"
          placeholder="キャプション"
          readonly
        >{{ $memory->caption }}</textarea>
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
          readonly
        >{{ $memory->entry }}</textarea>
        <div class="underline-container">
          <div class="underline" style="top: 2rem"></div>
        </div>
      </div>
    </div>
  </div>
</main>