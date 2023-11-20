<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Friendship;
use App\Models\Memory;
use App\Models\User;
use App\Traits\LikesHandlingTrait;


class FriendController extends Controller
{
    use LikesHandlingTrait;

    //
    /**
     * 友達ページ表示
     * 
     * @param int $friendId
     * @return view
     */
    public function showFriend($friendId)
    {
        // 友達のユーザーデータを取得
        $friend = User::find($friendId);
        if (!$friend) {
            // 友達が見つからない場合はエラーハンドリング
            return redirect()->back()->withErrors('Friend not found.');
        }

        // 過去24時間以内の友達のメモリーを取得
        $hoursAgo24 = Carbon::now()->subHours(24);
        $recentFriendMemories = Memory::where('user_id', $friendId)
                                    ->where('created_at', '>=', $hoursAgo24)
                                    ->latest()
                                    ->get();

        // 友達のメモリーを取得
        $friendMemories = Memory::where('user_id', $friendId)->latest()->get();

        // ビューにデータを渡す
        return view('diary.friend', [
            'friend' => $friend,
            'allMemories' => $friendMemories, // 友達のすべてのメモリー
            'recentMemories' => $recentFriendMemories, // 友達の最新のメモリー
            'friendMemories' => $friendMemories
        ]);
    }

    /**
     * 友達追加
     * @param  Request $request
     */
    public function addFriend(Request $request)
    {
        $friendId = $request->input('friend_id'); // HTMLのフォームから友達のIDを取得

        // 現在のユーザーIDを取得
        $userId = Auth::id();

        // レコードを作成
        $friendship = new Friendship;
        $friendship->user_id = $userId;
        $friendship->friend_id = $friendId;
        $friendship->save();

        // リダイレクトなど、適切なレスポンスを返す
        return redirect()->back()->with('message', '友達が追加されました。');
    }
    /**
     * 友達削除
     * @param  Request $request
     */
    public function deleteFriend(Request $request)
    {
        $friendId = $request->input('friend_id'); // HTMLのフォームから友達のIDを取得
        // 現在のユーザーIDを取得
        $userId = Auth::id();

        // 対象の友達関係を削除
        $friendship = Friendship::where('user_id', $userId)
                                ->where('friend_id', $friendId)
                                ->first();

        if ($friendship) {
            $friendship->delete();
            return response()->json(['message' => 'フォローを解除しました']);
        }

        return response()->json(['message' => 'エラーが発生しました'], 400);
    }

    /**
     * 友達検索
     * @return view
     */
    public function searchFriend()
    {
        return view('diary.search');
    }

    /**
     * 友達検索結果
     * @param  Request $request
     */
    public function searchResult(Request $request)
    {
        // 検索クエリを取得
        $query = $request->input('query');

        // 現在のユーザーIDを取得
        $currentUserId = Auth::id();

        $results = User::where('username', '=', $query)
                        ->where('id', '!=', $currentUserId)
                        ->get(['id', 'name', 'profile_pic']);
                        // dd($results);

        return view('diary.search', ['results' => $results]);
    }

    /**
     * 友達リクエスト送信
     * @param  Request $request
     */
    public function sendFriendRequest(Request $request)
    {
        $friendId = $request->input('friend_id');
        Log::info('Received Friend ID:', ['friendId' => $friendId]); // ログ出力で確認
        $userId = Auth::id();

        // 既に存在するリクエストをチェック
        $existingRequest = Friendship::where([
            ['user_id', $userId],
            ['friend_id', $friendId]
        ])->first();

        if ($existingRequest) {
            return response()->json(['message' => '既にリクエストが存在します。'], 400);
        }

        // 新しいリクエストを作成
        $friendship = new Friendship();
        $friendship->user_id = $userId;
        $friendship->friend_id = $friendId;
        $friendship->status = 'pending';
        $friendship->save();

        return response()->json(['message' => '友達リクエストを送信しました。']);
    }

    //
    /**
     * 友達のステータス確認
     * 
     * @param int $friendId
     * @return response
     */
    public function checkStatus(Request $request)
    {
        $userId = Auth::id();
        $friendId = $request->input('friend_id');

        $isFollowing = Friendship::where([
            ['user_id', $userId],
            ['friend_id', $friendId]
        ])->first();

        return response()->json(['isFollowing' => $isFollowing]);
    }

    /**
     * 友達のメモリーのデータを取得
     * @param  Request $request
     */
    public function getFriendMemories($friendId)
    {
        $friendMemories = Memory::with('user')
                            ->where('user_id', $friendId)
                            ->where('created_at', '>=', Carbon::now()->subHours(24))
                            ->latest()
                            ->get();

        return response()->json($friendMemories);
    }

}
