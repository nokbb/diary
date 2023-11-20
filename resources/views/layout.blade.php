<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- fontawesome -->
    <script
      src="https://kit.fontawesome.com/50334caab0.js"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}" />
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <title>@yield('title')</title>
  </head>
  <body data-home-url="{{ route('home') }}" data-camera-url="{{ route('camera') }}">
    
    
    <!-- jQuery -->
    <script
      src="https://code.jquery.com/jquery-3.7.0.min.js"
      integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g="
      crossorigin="anonymous"
    ></script>
    <!-- calendar.js -->
    <!-- <script src="{{ asset('js/calendar.js') }}"></script> -->
    <!-- app.js -->
    <script src="{{ mix('js/app.js') }}" type="module"></script>
    <!-- js -->
    <script src="{{ asset('js/script.js') }}" type="module"></script>
  </body>
</html>
