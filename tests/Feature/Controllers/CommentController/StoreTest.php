<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it("requires authentication", function () {
    post(route("posts.comments.store", Post::factory()->create()))->assertRedirect("login");
});

it("can create a comment", function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    $post = Post::factory()->create();

    actingAs($user)->post(route("posts.comments.store", $post), [
        "body" => "This is a comment",
    ]);

    $this->assertDatabaseHas(Comment::class, [
        "user_id" => $user->id,
        "post_id" => $post->id,
        "body" => "This is a comment",
    ]);
});

it("redirects to the post show page", function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    $post = Post::factory()->create();

    actingAs($user)->post(route("posts.comments.store", $post), [
        "body" => "This is a comment",
    ])
        ->assertRedirect($post->showRoute());
});

it("requires a valid body", function ($value) {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    $post = Post::factory()->create();

    actingAs($user)
        ->post(route("posts.comments.store", $post), ["body" => $value])
        ->assertInvalid("body");
})->with([
    null,
    1,
    1.5,
    true,
    str_repeat("a", 2501),
]);
