<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Memory;
use App\Models\Category;


class CameraCaptureController extends Controller
{
    //
    /**
     * アップロード
     * 
     * @return response
     */
    public function showFileUpload(Request $request)
    {
        if ($request->hasFile('file')) {
            // ファイルを保存する等の処理
            $file = $request->file('file');
            $filename = $file->store('uploads', 'public'); // 'public'ディスクを指定

            // 保存されたファイルのパスとログインユーザーのIDをDBに保存
            $memory = new Memory();
            $memory->user_id = Auth::id(); // 現在ログインしているユーザーのID
            $memory->file_path = $filename; // 保存されたファイルのパス
            // キャプションとエントリーを保存
            $memory->caption = $request->input('caption');
            $memory->entry = $request->input('entry');
            $memory->category_id = $request->input('category_id');
            $memory->save();

            return response()->json([
                'message' => 'File uploaded and saved successfully',
                'file_path' => $filename // アップロードされたファイルのパス
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }


    /**
     * カテゴリーによる一覧表示
     */
    public function category($name)
    {
        $category = Category::where('name', $name)->first();
        if ($category) {
            $memories = Memory::where('category_id', $category->id)->get();
            return view('diary.category', [
                'category' => $category,
                'memories' => $memories
            ]);
        } else {
            return redirect('/')->withErrors(['category' => 'Category not found']);
        }
    }

    /**
     * すべてのカテゴリーとそれに属するメモリを取得
     */
    public function getAllCategoriesWithMemories()
    {
        $categories = Category::with('memories')->get();
        return response()->json($categories);
    }

    /**
     * ユーザーに紐づくカテゴリーデータを取得
     */
    public function getUserCategories()
    {
        // 現在のログインユーザーを取得
        $user = Auth::user();
        $categories = Category::where('user_id', $user->id)->get(); // ユーザーに紐づくカテゴリーを取得
        return response()->json($categories);
    }


    /**
     * カテゴリーの追加
     */
    public function addCategory(Request $request)
    {
        // バリデーションを実施
        $validatedData = $request->validate([
            'name' => 'required|string|max:255', // 例えば、カテゴリ名は必須とします
        ]);

        // カテゴリーモデルを作成
        $category = new Category();
        $category->name = $validatedData['name'];
        $category->user_id = auth()->user()->id; 
        $category->save();

        // 作成されたカテゴリーをレスポンスとして返す
        return response()->json($category, 201); // HTTP 201 Created
    }

    /**
     * カテゴリーの削除
     */
    public function removeCategory(Category $category)
    {
        // カテゴリーを削除
        $category->delete();

        // 削除成功のレスポンス
        return response()->json(null, 204); // HTTP 204 No Content
    }


    /**
     * アップロード画面からカテゴリー追加
     */
    public function add(Request $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->user_id = auth()->user()->id; 
        $category->save();

        return response()->json($category);
    }


}
