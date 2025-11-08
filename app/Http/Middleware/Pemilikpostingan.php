<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Pemilikpostingan
{
    public function handle(Request $request, Closure $next)
    {
        $post = Post::find($request->route('id')); // ambil post dari route
        if (!$post) {
            return response()->json(['message' => 'Postingan tidak ditemukan'], 404);
        }

        if ($post->author !== auth()->id()) {
            return response()->json(['message' => 'Anda bukan pemilik postingan ini'], 403);
        }

        return $next($request);
    }
}
