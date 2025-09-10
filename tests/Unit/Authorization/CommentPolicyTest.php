<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use Mockery;
use App\Models\User;
use App\Models\Comment;
use App\UserTypes;
use App\Policies\CommentPolicy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

beforeEach(function (): void {
    $this->policy = new CommentPolicy();
});

test('de delete methode staat het verwijderen toe als de gebruiker de auteur van de reactie is', function (): void {
    // Arrange
    $user = Mockery::mock(User::class);
    $comment = Mockery::mock(Comment::class);
    $relationship = Mockery::mock(BelongsTo::class);

    $comment->shouldReceive('getAttribute')->with('commentator')->andReturn($relationship);
    $comment->shouldReceive('commentator')->andReturn($relationship);
    $relationship->shouldReceive('is')->once()->with($user)->andReturn(true);
    $user->shouldNotReceive('getAttribute')->with('user_type');

    // Act
    $response = $this->policy->delete($user, $comment);

    // Assert
    expect($response->allowed())->toBeTrue();
});

test('de delete methode staat het verwijderen toe als de gebruiker een ontwikkelaar is', function (): void {
    // Arrange
    $user = Mockery::mock(User::class);
    $comment = Mockery::mock(Comment::class);
    $commentatorUser = Mockery::mock(User::class);
    $relationship = Mockery::mock(BelongsTo::class);

    $comment->shouldReceive('getAttribute')->with('commentator')->andReturn($relationship);
    $comment->shouldReceive('commentator')->andReturn($relationship);
    $relationship->shouldReceive('is')->once()->with($user)->andReturn(false);
    $user->shouldReceive('getAttribute')->with('user_type')->andReturn(UserTypes::Developer);

    // Act
    $response = $this->policy->delete($user, $comment);

    // Assert
    expect($response->allowed())->toBeTrue();
});

test('de delete methode staat het verwijderen toe als de gebruiker een beheerder is', function (): void {
    // Arrange
    $user = Mockery::mock(User::class);
    $comment = Mockery::mock(Comment::class);
    $relationship = Mockery::mock(BelongsTo::class);

    $comment->shouldReceive('getAttribute')->with('commentator')->andReturn($relationship);
    $comment->shouldReceive('commentator')->andReturn($relationship);
    $relationship->shouldReceive('is')->once()->with($user)->andReturn(false);
    $user->shouldReceive('getAttribute')->with('user_type')->andReturn(UserTypes::Administrators);

    // Act
    $response = $this->policy->delete($user, $comment);

    // Assert
    expect($response->allowed())->toBeTrue();
});

test('de delete methode weigert het verwijderen als de gebruiker niet de auteur is en geen beheerder of ontwikkelaar is', function (): void {
    // Arrange
    $user = Mockery::mock(User::class);
    $comment = Mockery::mock(Comment::class);
    $relationship = Mockery::mock(BelongsTo::class);

    $comment->shouldReceive('getAttribute')->with('commentator')->andReturn($relationship);
    $comment->shouldReceive('commentator')->andReturn($relationship);
    $relationship->shouldReceive('is')->once()->with($user)->andReturn(false);
    $user->shouldReceive('getAttribute')->with('user_type')->andReturn(UserTypes::Normal);

    // Act
    $response = $this->policy->delete($user, $comment);

    // Assert
    expect($response->denied())->toBeTrue()
        ->and($response->status())->toBe(404);
});
