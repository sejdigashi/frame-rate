<?php

use App\Models\Comment;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;

it("requires authentication", function () {
    delete(route("comments.destroy", Comment::factory()->create()))->assertRedirect("login");
});

it("can delete a comment", function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)->delete(route("comments.destroy", $comment));
    $this->assertModelMissing($comment);
});

it("redirects to the post show page", function () {
    $comment = Comment::factory()->create();
    actingAs($comment->user)->delete(route("comments.destroy", $comment))->assertRedirect($comment->post->showRoute());

});

it("prevents deleting a comment you didn't create", function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    $comment = Comment::factory()->create();

    actingAs($user)->delete(route("comments.destroy", $comment))->assertForbidden();
});

it("prevents deleting a comment posted over an hour ago", function () {
    $this->freezeTime();
    $comment = Comment::factory()->create();

    $this->travel(1)->hour();
    actingAs($comment->user)->delete(route("comments.destroy", $comment))->assertForbidden();
});

it("redirects to the post show page with the page query paramater", function () {
    $comment = Comment::factory()->create();
    actingAs($comment->user)->delete(route("comments.destroy", ["comment" => $comment, "page" => 2]))
        ->assertRedirect($comment->post->showRoute(["page" => 2]));

});
