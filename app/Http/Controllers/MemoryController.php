<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Memory;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use App\Traits\LikesHandlingTrait;


class MemoryController extends Controller
{
    use LikesHandlingTrait;

    //
    /**
     * メモリー一覧表示
     * 
     * @return view
     */
    public function showMemories()
    {
        // 現在のログインユーザーを取得
        $user = Auth::user();

        // Memoryモデルを使用して、ログインユーザーのメモリーを取得
        $memories = Memory::where('user_id', $user->id)->get();

        // それぞれのメモリーに対していいねのステータスを取得
        foreach ($memories as $memory) {
            $memory->likeStatus = $this->checkLikeStatusMemories($memory);
        }

        return view('diary.memories', ['user' => $user, 'memories' => $memories]);
    }

    //
    /**
     * メモリー一覧表示API
     * 
     * @return view
     */
    public function showApiMemories()
    {
        // 現在のログインユーザーを取得
        $user = Auth::user();

        $memories = Memory::where('user_id', $user->id)->get(); // すべてのメモリーを取得

        return response()->json($memories);
    }

    //
    /**
     * メモリー詳細表示
     * 
     * @return view
     */
    public function showMemoryDetail($id)
    {
        $memory = Memory::find($id);

        return view('diary.memory_detail', ['memory' => $memory]);
    }

    //
    /**
     * メモリー詳細編集
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateMemory(Request $request, $id)
    {
        // バリデーションルールを設定
        $validatedData = $request->validate([
            'caption' => 'string|max:255', // キャプションは文字列で255文字以内
            'entry' => 'string', // エントリーは文字列
        ]);

        $memory = Memory::find($id);

        // メモリーが存在する場合は更新
        if ($memory) {
            $memory->update($validatedData);

            // JSONレスポンスで成功メッセージを返す
            return response()->json([
                'success' => true,
                'message' => 'メモリーが正常に更新されました。',
                'memory' => $memory
            ]);
        }

        // メモリーが見つからない場合は、JSONレスポンスでエラーメッセージを返す
        return response()->json([
            'success' => false,
            'message' => 'メモリーが見つかりませんでした。'
        ], 404); // 404 Not Foundステータスコードを返す

    }

    //
    /**
     * メモリー詳細削除
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteMemory($id)
    {
        $memory = Memory::find($id);

        // メモリーが存在する場合は削除
        if ($memory) {
            $memory->delete();

            // JSONレスポンスで成功メッセージを返す
            return response()->json([
                'success' => true,
                'message' => 'メモリーが正常に削除されました。'
            ]);
        }

        // メモリーが見つからない場合は、JSONレスポンスでエラーメッセージを返す
        return response()->json([
            'success' => false,
            'message' => 'メモリーが見つかりませんでした。'
        ], 404); // 404 Not Foundステータスコードを返す

    }

    //
    /**
     * いいねを追加
     * 
     * @param  int  
     * @return \Illuminate\Http\Response
     */
    public function memoriesLike(Request $request, Memory $memory)
    {
        // すでにいいねが存在するかチェック
        $existingLike = Like::where('user_id', auth()->id())->where('photo_id', $memory->id)->first();

        if (!$existingLike) {
            $like = Like::create([
                'user_id' => auth()->id(),
                'photo_id' => $memory->id
            ]);

            $user = Auth::user();

            // このメモリーに対する全ての「いいね」を取得して、プロフィール画像の配列を作成
            $likes = Like::where('photo_id', $memory->id)->with('user')->get();
            $profilePics = $likes->map(function ($like) {
                return $like->user ? $like->user->profile_pic : null;
            });

            return response()->json([
                'message' => 'Liked',
                'profile_pics' => $profilePics
            ]);
        } else {
            return response()->json(['message' => 'Already liked']);
        }
    }

    //
    /**
     * いいねのステータス確認
     * 
     * @param  int  
     * @return \Illuminate\Http\Response
     */
    public function checkLikeStatus(Request $request, Memory $memory)
    {
        // このメモリーに対する全ての「いいね」を取得
        $likes = Like::where('photo_id', $memory->id)->with('user')->get();

        // 「いいね」に関連するユーザーのプロフィール画像の配列を作成
        $profilePics = $likes->map(function ($like) {
            return $like->user ? $like->user->profile_pic : null; // プロフィール画像があればそれを、なければ null を返す
        });

        // 現在のユーザーがこのメモリーに「いいね」しているかどうかを確認
        $isLiked = $likes->contains('user_id', Auth::id());

        return response()->json([
            'liked' => $isLiked,
            'profile_pics' => $profilePics // プロフィール画像の配列をレスポンスに含める
        ]);
    }

    //
    /**
     * いいね削除
     * 
     * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Memory  $memory
    * @return \Illuminate\Http\Response
     */
    public function memoriesUnlike(Request $request, Memory $memory)
    {
        $user = Auth::user(); // 現在のユーザーを取得

        Like::where('user_id', $user->id)
            ->where('photo_id', $memory->id)
            ->delete();

        // レスポンスに現在のユーザーのプロフィール画像を含める
        return response()->json([
            'message' => 'Unliked',
            'currentUserPic' => $user->profile_pic // 現在のユーザーのプロフィール画像
        ]);
    }

}
