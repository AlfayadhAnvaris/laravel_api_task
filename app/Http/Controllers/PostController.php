<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostDetailResource;
use App\Models\Post;
use File;
use Illuminate\Http\File as HttpFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with([
            'writer:id,username',
            'comments:id,post_id,user_id,comments_content'
        ])->get();

        return PostDetailResource::collection($posts);
    }

    public function show($id)
    {
        $post = Post::with([
            'writer:id,username',
            'comments:id,post_id,user_id,comments_content'
        ])->findOrFail($id);

        return new PostDetailResource($post);
    }

  public function create(Request $request)
{
    $validated = $request->validate([
        'title' => 'required',
        'news_content' => 'required',
        'file' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
    ]);

    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $fileName = $this->generateRandomString() . '.' . $file->getClientOriginalExtension();
        // Simpan ke storage/app/public/images
        $file->storeAs('images', $fileName, 'public');
        $validated['image'] = $fileName;
    }

    $validated['author'] = auth()->user()->id;

    $post = Post::create($validated);

    return new PostDetailResource($post->load('writer:id,username'));
}



    public function edit(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required',
            'news_content' => 'required',
        ]);

        $post = Post::findOrFail($id);
        $post->update($validated);

        return new PostDetailResource(
            $post->load('writer:id,username')
        );
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json([
            'message' => 'Post berhasil dihapus'
        ]);
    }

    function generateRandomString($length = 30) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
}
}
