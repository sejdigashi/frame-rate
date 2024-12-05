<?php

use App\Models\Post;
use function Pest\Laravel\get;
use Illuminate\Support\Str;

it("uses title case for titles", function () {
    $post = Post::factory()->create(["title" => "Hi how are you?"]);

    expect($post->title)->toBe("Hi How Are You?");
});

it("can generate a route to the show page", function () {
    $post = Post::factory()->create();

    expect($post->showRoute())->toBe(route("posts.show", [$post, Str::slug($post->title)]));
});

it("will redirect if the slug is incorrect", function () {
    $post = Post::factory()->create(["title" => "Hello world"]);

    get(route("posts.show", [$post, "foo-bar", "page" => 2]))->assertRedirect($post->showRoute(["page" => 2]));
});

it("generates the html", function () {
    $post = Post::factory()->make(["body" => "## Hello world"]);
    $post->save();

    expect($post->html)->toEqual(str($post->body)->markdown());
});
