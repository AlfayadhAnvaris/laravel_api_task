<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Tambah komentar
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comments_content' => 'required|string',
        ]);

        $user = auth()->user();

        $comment = Comment::create([
            'post_id' => $validated['post_id'],
            'user_id' => $user->id,
            'comments_content' => $validated['comments_content'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditambahkan!',
            'data' => [
                'id' => $comment->id,
                'post_id' => $comment->post_id,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                ],
                'created_at' => $comment->created_at->toDateTimeString(),
            ]
        ], 201);
    }

    // Update komentar
    public function save(Request $request, $id)
    {
        $validated = $request->validate([
            'comments_content' => 'required|string',
        ]);

        $comment = Comment::findOrFail($id);

        $comment->update([
            'comments_content' => $validated['comments_content'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil diupdate!',
            'data' => $comment
        ]);
    }

    // Delete komentar
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus!',
        ]);
    }
}
