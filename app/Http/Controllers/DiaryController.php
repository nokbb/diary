<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Memory;
use App\Models\Friendship;
use Carbon\Carbon;
use App\Traits\LikesHandlingTrait;


class DiaryController extends Controller
{
    use LikesHandlingTrait;

    //
    /**
     * ホーム画面を表示する
     * 
     * @return view
     */
    public function showHome()
    {
        // 現在のログインユーザーを取得 ログインしていなければ null
        $user = Auth::user();
        $memory = null;
        $friendMemories = collect();

        // ログインしている場合のみ、メモリーを取得
        if ($user) {
            $hoursAgo24 = Carbon::now()->subHours(24); // 現在時刻から24時間以内のDateTimeインスタンスを取得
            $memory = Memory::where('user_id', $user->id)
                            ->where('created_at', '>=', $hoursAgo24)
                            ->latest()
                            ->first();

            // 友達のメモリーを取得
            $friendships = Friendship::where('user_id', $user->id)->get();
            foreach ($friendships as $friendship) {
                $friendId = $friendship->friend_id;
                $friendMemory = Memory::with('user')
                                    ->where('user_id', $friendId)
                                    ->where('created_at', '>=', $hoursAgo24)
                                    ->latest()
                                    ->first();

                if ($friendMemory) {
        $likeStatus = $this->checkLikeStatus($friendMemory);
        // デバッグ用
        \Log::info('Like Status for Memory ID ' . $friendMemory->id, $likeStatus);
        $friendMemory->likeStatus = $likeStatus;
        $friendMemories->push($friendMemory);
    }

            }
        }

        return view('diary.home', [
            'user' => $user, 
            'memory' => $memory,
            'friendMemories' => $friendMemories
        ]);
    }

    //
    /**
     * カメラ画面を表示する
     * 
     * @return view
     */
    public function showCamera()
    {
        return view('diary.camera');
    }

    //
    /**
     * マイページ画面を表示する
     * 
     * @return view
     */
    public function showMypage()
    {
        // 現在のログインユーザーを取得
        $user = Auth::user();

        // Memoryモデルを使用して必要なデータを取得
        $memories = Memory::where('user_id', $user->id)->get();

        // それぞれのメモリーに対していいねのステータスを取得
        foreach ($memories as $memory) {
            $memory->likeStatus = $this->checkLikeStatus($memory);
        }

        return view('diary.mypage', ['user' => $user, 'memories' => $memories]);
    }

    //
    /**
     * マイページ編集画面を表示する
     * 
     * @return view
     */
    public function showMypageEdit()
    {
        // 現在のログインユーザーを取得
        $user = Auth::user();
        
        return view('diary.mypage_edit', ['user' => $user]);
    }

    //
    /**
     * マイページ更新処理
     * 
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showMypageUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users,username,' . Auth::id(),
        ]);

        // 現在のログインユーザーを取得
        $user = Auth::user();

        //ユーザー情報を更新
        // $user->profile_pic = $request->profile_pic;
        $user->name = $request->name;
        $user->username = $request->username;

        // プロフィール画像の処理
        if ($request->hasFile('profile_pic')) {
            // 既存の画像が存在する場合、削除する
            if ($user->profile_pic) {
                Storage::delete('public/profile_pics/' . $user->profile_pic);
            }

            // 新しい画像を保存する
            $filename = $request->file('profile_pic')->store('public/profile_pics');
            $user->profile_pic = basename($filename);
        }

        $user->save();

        //更新完了のメッセージをセッションに保存
        session()->flash('success', 'マイページ情報が更新されました。');

        return redirect()->route('mypage');
    }

    //
    /**
     * プロフィール画像削除
     * 
     * @param  Request $request
     * @return 
     */
    public function deleteProfilePic(Request $request)
    {
        try {
            $user = Auth::user();

            //画像が存在すれば削除
            if ($user->profile_pic) {
                Storage::disk('public')->delete('profile_pics/' . $user->profile_pic);
                $user->profile_pic = null;
                $user->save();
            }

            return response()->json(['success' => true, 'message' => 'プロフィール画像を削除しました。']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'エラーが発生しました。再試行してください。'], 500);
        }
    }
}
