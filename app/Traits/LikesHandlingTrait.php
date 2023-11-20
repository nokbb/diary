<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Friendship;
use App\Models\Memory;
use App\Models\Like;

trait LikesHandlingTrait {
    /**
     * いいねのステータス確認メモリー詳細
     *
     * @param  \App\Models\Memory  $memory
     * @return array
     */
    public function checkLikeStatusMemories($memory)
    {
        $likes = Like::where('photo_id', $memory->id)->with('user')->get();

        $profilePics = $likes->map(function ($like) {
            return $like->user ? $like->user->profile_pic : null;
        });

        $isLiked = $likes->contains('user_id', Auth::id());

        return [
            'liked' => $isLiked,
            'profile_pics' => $profilePics
        ];
    }

    /**
     * いいねのステータス確認
     *
     * @param  \App\Models\Memory  $memory
     * @return array
     */
    public function checkLikeStatus($memory)
    {
        $likes = Like::where('photo_id', $memory->id)->with('user')->get();

        $profilePics = $likes->map(function ($like) {
            return $like->user ? $like->user->profile_pic : null;
        });

        $isLiked = $likes->contains('user_id', Auth::id());

        return [
            'liked' => $isLiked,
            'profile_pics' => $profilePics
        ];
    }

}
