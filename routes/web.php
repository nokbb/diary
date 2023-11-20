<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController; //この行を追加
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemoryController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\CameraCaptureController;
use App\Http\Controllers\FriendController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('scss', function () {
  return view('login');
});
Route::get('scss', function () {
  return view('signup');
});
Route::get('scss', function () {
  return view('main');
});
Route::get('scss', function () {
  return view('camera');
});
Route::get('scss', function () {
  return view('mypage');
});
Route::get('scss', function () {
  return view('mypage_edit');
});


//ログイン前でなければ入れない
Route::middleware(['guest'])->group(function () {
  //ログイン画面
  Route::get('/', [LoginController::class, 'showLogin'])->name('showLogin');
  //ログイン処理
  Route::post('login', [LoginController::class, 'login'])->name('login');

  //新規登録画面
  Route::get('/register', [UserController::class, 'showRegister'])->name('showRegister');
  //新規登録処理
  Route::post('register', [UserController::class, 'register'])->name('register');

  //パスワードリセットメール送信フォーム画面を表示する
  Route::get('/reset', [PasswordController::class, 'emailFormResetPassword'])->name('form');
  //メール送信処理
  Route::post('/reset', [PasswordController::class, 'sendEmailResetPassword'])->name('send');
  //メール送信完了画面
  Route::get('/send-complete', [PasswordController::class, 'sendComplete'])->name('send_complete');


  //パスワード再設定画面
  Route::get('/resetting', [PasswordController::class, 'edit'])->name('edit');
  //パスワード更新処理
  Route::post('/update', [PasswordController::class, 'update'])->name('update');
  //パスワード更新完了画面
  Route::get('/update', [PasswordController::class, 'edited'])->name('edited');
});


//ログイン後でなければ入れない
Route::middleware(['auth'])->group(function () {
  //ログアウト
  Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
  //マイページ編集
  Route::get('/mypage_edit', [DiaryController::class, 'showMypageEdit'])->name('mypageEdit');
  //マイページ更新
  Route::patch('/mypage_update', [DiaryController::class, 'showMypageUpdate'])->name('mypageUpdate');
  //プロフィール画像削除
  Route::post('/profile-pic/delete', [DiaryController::class, 'deleteProfilePic'])->name('deleteProfilePic');
  //メモリー一覧
  Route::get('/memories', [MemoryController::class, 'showMemories'])->name('memories');
  //メモリー一覧API
  Route::get('/api/memories', [MemoryController::class, 'showApiMemories'])->name('ApiMemories');
  //メモリー詳細
  Route::get('/memory_detail/{id}', [MemoryController::class, 'showMemoryDetail'])->name('memoryDetail');
  //メモリー編集
  Route::patch('/api/update/{id}', [MemoryController::class, 'updateMemory'])->name('updateMemory');
  //メモリー削除
  Route::delete('/api/delete/{id}', [MemoryController::class, 'deleteMemory'])->name('deleteMemory');
  //いいね追加
  Route::post('/memories/{memory}/like', [MemoryController::class, 'memoriesLike'])->name('memoriesLike');
  //いいねのステータス確認
  Route::get('/memories/{memory}/like-status', [MemoryController::class, 'checkLikeStatus']);
  //いいね削除
  Route::delete('/memories/{memory}/like', [MemoryController::class, 'memoriesUnlike'])->name('memoriesUnlike');
  //ファイルアップロード
  Route::post('/fileupload', [CameraCaptureController::class, 'showFileUpload'])->name('fileupload');
  //アップロード画面からカテゴリー追加
  Route::post('/categories/add', [CameraCaptureController::class, 'add'])->name('add');
  //カテゴリー
  Route::get('/memories/category/{name}', [CameraCaptureController::class, 'category'])->name('category');
  // すべてのカテゴリーとそれに属するメモリを取得するエンドポイント
  Route::get('/api/categories', [CameraCaptureController::class, 'getAllCategoriesWithMemories'])->name('getAllCategoriesWithMemories');
  // ユーザーに紐づくカテゴリーデータを取得するアクション
  Route::get('/api/users/categories', [CameraCaptureController::class, 'getUserCategories'])->name('getUserCategories');
  // カテゴリー追加
  Route::post('/api/categories', [CameraCaptureController::class, 'addCategory'])->name('addCategory');
  // カテゴリー削除
  Route::delete('/api/categories/{category}', [CameraCaptureController::class, 'removeCategory'])->name('removeCategory');

});

//ホーム画面
Route::get('/home', [DiaryController::class, 'showHome'])->name('home');

//カメラ
Route::get('/camera', [DiaryController::class, 'showCamera'])->name('camera');




//マイページ
Route::get('/mypage', [DiaryController::class, 'showMypage'])->name('mypage');
// 友達追加
Route::post('/api/friend/add/', [FriendController::class, 'addFriend'])->name('friend.add');
// 友達追加リクエスト送信
Route::post('/api/friend/request', [FriendController::class, 'sendFriendRequest'])->name('sendFriendRequest');
// 友達リクエストのステータス確認
Route::get('/api/friend/check-status', [FriendController::class, 'checkStatus'])->name('checkStatus');
// 友達削除
Route::post('/api/friend/delete', [FriendController::class, 'deleteFriend'])->name('friend.delete');
// 友達検索
Route::get('/friend/search', [FriendController::class, 'searchFriend'])->name('friend.search');
// 友達検索結果
Route::get('/friend/searchResult', [FriendController::class, 'searchResult'])->name('search.results');
//友達ページ
Route::get('/friend/{friendId}', [FriendController::class, 'showFriend'])->name('friend.show');
//友達のメモリーのデータを取得
Route::get('/api/friend-memories/{friendId}', [FriendController::class, 'getFriendMemories'])->name('getFriendMemories');
