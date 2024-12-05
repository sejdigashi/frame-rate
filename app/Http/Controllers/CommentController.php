<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CommentController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->authorizeResource(Comment::class);
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            "body" => ["required", "string", "max:2500"],
        ]);

        $comment = Comment::make($data)
            ->user()->associate($request->user())
            ->post()->associate($post)
            ->save();

        return redirect($post->showRoute())->banner('Comment added.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $data = $request->validate([
            "body" => ["required", "string", "max:2500"],
        ]);

        $comment->update($data);

        return redirect($comment->post->showRoute(['page' => $request->query('page')]))
            ->banner('Comment updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Comment $comment)
    {
        $comment->delete();

        return redirect($comment->post->showRoute(['page' => $request->query('page')]))
            ->banner('Comment deleted.');
    }
}
