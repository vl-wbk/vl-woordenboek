<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mockery;
use App\Models\Blog;
use App\Models\User;
use App\Policies\BlogPolicy;
use Illuminate\Auth\Access\Response;

beforeEach(function (): void {
    $this->policy = new BlogPolicy();
});

test('de before methode weigert de toegang voor gebruikers zonder de page_Blog permissie', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('cannot')->once()->with('page_Blog')->andReturn(true);

    $response = $this->policy->before($user);

    expect($response)->toBeInstanceOf(Response::class)
        ->and($response->status())->toBe(404);
});

test('de before methode staat de toegang toe voor gebruikers met de page_Blog permissie', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('cannot')->once()->with('page_Blog')->andReturn(false);

    $response = $this->policy->before($user);

    expect($response)->toBeNull();
});

test('de submitPost method staat het indienen toe als de gebruiker een geverifieerd e-mail adres heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('hasVerifiedEmail')->once()->andReturn(true);

    $response = $this->policy->submitPost($user);

    expect($response->allowed())->toBeTrue();
});

test('de submitPost method weigert de toegang als de gebruiker geen geverifieerd e-mail adres heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('hasVerifiedEmail')->once()->andReturn(false);

    $response = $this->policy->submitPost($user);

    expect($response->denied())->toBeTrue()
        ->and($response->status())->toBe(404);
});

test('de viewAny methode staat de toegang toe als de gebruiker de view_any_blog permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('view_any_blog')->andReturn(true);

    $response = $this->policy->viewAny($user);

    expect($response->allowed())->toBeTrue();
});

test('de viewAny methode weigert de toegang als de gebruiker de view_any_blog permissie niet heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('view_any_blog')->andReturn(false);

    $response = $this->policy->viewAny($user);

    expect($response->denied())->toBeTrue()
        ->and($response->status())->toBe(404);
});

test('de view methode staat de toegang toe als de gebruiker de view_blog permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $blog = Mockery::mock(Blog::class);
    $user->shouldReceive('can')->once()->with('view_blog')->andReturn(true);

    $response = $this->policy->view($user, $blog);

    expect($response->allowed())->toBeTrue();
});

test('de view methode weigert de toegang als de gebruiker de view_blog permissie niet heeft', function (): void {
    $user = Mockery::mock(User::class);
    $blog = Mockery::mock(Blog::class);
    $user->shouldReceive('can')->once()->with('view_blog')->andReturn(false);

    $response = $this->policy->view($user, $blog);

    expect($response->denied())->toBeTrue()
        ->and($response->status())->toBe(404);
});

test('de canComment methode staat het reageren toe als opmerkingen zijn ingeschakeld en de gebruiker geverifieerd is', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('hasVerifiedEmail')->andReturn(true);

    $blog = Mockery::mock(Blog::class)->shouldAllowMockingProtectedMethods()->makePartial();
    $blog->comments_enabled = true;

    $response = $this->policy->canComment($user, $blog);

    expect($response->allowed())->toBeTrue();
});

test('de canComment methode weigert het reageren als opmerkingen zijn uitgeschakeld', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('hasVerifiedEmail')->andReturn(true);

    $blog = Mockery::mock(Blog::class)->shouldAllowMockingProtectedMethods()->makePartial();
    $blog->comments_enabled = false;

    $response = $this->policy->canComment($user, $blog);

    expect($response->denied())->toBeTrue()
        ->and($response->status())->toBe(404);
});

test('de canComment methode weigert het reageren als de gebruiker niet geverifieerd is', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('hasVerifiedEmail')->andReturn(false);

    $blog = Mockery::mock(Blog::class)->shouldAllowMockingProtectedMethods()->makePartial();
    $blog->comments_enabled = true;

    $response = $this->policy->canComment($user, $blog);

    expect($response->denied())->toBeTrue()
        ->and($response->status())->toBe(404);
});

test('de update methode staat de toegang toe als de gebruiker de auteur is', function (): void {
    $user = Mockery::mock(User::class);
    $blog = Mockery::mock(Blog::class);
    $author = Mockery::mock(BelongsTo::class);

    $blog->shouldReceive('author')->once()->andReturn($author);
    $author->shouldReceive('is')->once()->with($user)->andReturn(true);
    $user->shouldNotReceive('can');

    $response = $this->policy->update($user, $blog);

    expect($response->allowed())->toBeTrue();
});

test('de update methode staat de toegang toe als de gebruiker de update_blog permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $blog = Mockery::mock(Blog::class);
    $author = Mockery::mock(BelongsTo::class);

    $blog->shouldReceive('author')->once()->andReturn($author);
    $author->shouldReceive('is')->once()->with($user)->andReturn(false);
    $user->shouldReceive('can')->once()->with('update_blog')->andReturn(true);

    $response = $this->policy->update($user, $blog);

    expect($response->allowed())->toBeTrue();
});

test('de update methode weigert de toegang als de gebruiker niet de auteur is en de update_blog permissie niet heeft', function (): void {
    $user = Mockery::mock(User::class);
    $blog = Mockery::mock(Blog::class);
    $author = Mockery::mock(BelongsTo::class);

    $blog->shouldReceive('author')->once()->andReturn($author);
    $author->shouldReceive('is')->once()->with($user)->andReturn(false);
    $user->shouldReceive('can')->once()->with('update_blog')->andReturn(false);

    $response = $this->policy->update($user, $blog);

    expect($response->denied())->toBeTrue()
        ->and($response->status())->toBe(404);
});

test('de publish methode staat het publiceren toe als de gebruiker de auteur is', function (): void {
    $user = Mockery::mock(User::class);
    $blog = Mockery::mock(Blog::class);
    $author = Mockery::mock(BelongsTo::class);

    $blog->shouldReceive('author')->once()->andReturn($author);
    $author->shouldReceive('is')->once()->with($user)->andReturn(true);

    $response = $this->policy->publish($user, $blog);

    expect($response->allowed())->toBeTrue();
});

test('de publish methode weigert het publiceren als de gebruiker niet de auteur is', function (): void {
    $user = Mockery::mock(User::class);
    $blog = Mockery::mock(Blog::class);
    $author = Mockery::mock(BelongsTo::class);

    $blog->shouldReceive('author')->once()->andReturn($author);
    $author->shouldReceive('is')->once()->with($user)->andReturn(false);

    $response = $this->policy->publish($user, $blog);

    expect($response->denied())->toBeTrue()
        ->and($response->status())->toBe(404);
});

test('de delete methode staat het verwijderen toe als de gebruiker de auteur is', function (): void {
    $user = Mockery::mock(User::class);
    $blog = Mockery::mock(Blog::class);
    $author = Mockery::mock(BelongsTo::class);

    $blog->shouldReceive('author')->once()->andReturn($author);
    $author->shouldReceive('is')->once()->with($user)->andReturn(true);
    $user->shouldNotReceive('can');

    $response = $this->policy->delete($user, $blog);

    expect($response->allowed())->toBeTrue();
});

test('de delete methode staat het verwijderen toe als de gebruiker de delete_blog permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $blog = Mockery::mock(Blog::class);
    $author = Mockery::mock(BelongsTo::class);

    $blog->shouldReceive('author')->once()->andReturn($author);
    $author->shouldReceive('is')->once()->with($user)->andReturn(false);
    $user->shouldReceive('can')->once()->with('delete_blog')->andReturn(true);

    $response = $this->policy->delete($user, $blog);

    expect($response->allowed())->toBeTrue();
});

test('de delete methode weigert het verwijderen als de gebruiker niet de auteur is en de delete_blog permissie niet heeft', function (): void {
    $user = Mockery::mock(User::class);
    $blog = Mockery::mock(Blog::class);
    $author = Mockery::mock(BelongsTo::class);

    $blog->shouldReceive('author')->once()->andReturn($author);
    $author->shouldReceive('is')->once()->with($user)->andReturn(false);
    $user->shouldReceive('can')->once()->with('delete_blog')->andReturn(false);

    $response = $this->policy->delete($user, $blog);

    expect($response->denied())->toBeTrue()
        ->and($response->status())->toBe(404);
});

test('de undoPublication methode staat het ongedaan maken toe als de gebruiker de auteur is', function (): void {
    $user = Mockery::mock(User::class);
    $blog = Mockery::mock(Blog::class);
    $author = Mockery::mock(BelongsTo::class);

    $blog->shouldReceive('author')->once()->andReturn($author);
    $author->shouldReceive('is')->once()->with($user)->andReturn(true);
    $user->shouldNotReceive('can');

    $response = $this->policy->undoPublication($user, $blog);

    expect($response->allowed())->toBeTrue();
});

test('de undoPublication methode staat het ongedaan maken toe als de gebruiker de undo_publication_blog permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $blog = Mockery::mock(Blog::class);
    $author = Mockery::mock(BelongsTo::class);

    $blog->shouldReceive('author')->once()->andReturn($author);
    $author->shouldReceive('is')->once()->with($user)->andReturn(false);
    $user->shouldReceive('can')->once()->with('undo_publication_blog')->andReturn(true);

    $response = $this->policy->undoPublication($user, $blog);

    expect($response->allowed())->toBeTrue();
});

test('de undoPublication methode weigert de toegang als de gebruiker niet de auteur is en de undo_publication_blog permissie niet heeft', function (): void {
    $user = Mockery::mock(User::class);
    $blog = Mockery::mock(Blog::class);
    $author = Mockery::mock(BelongsTo::class);

    $blog->shouldReceive('author')->once()->andReturn($author);
    $author->shouldReceive('is')->once()->with($user)->andReturn(false);
    $user->shouldReceive('can')->once()->with('undo_publication_blog')->andReturn(false);

    $response = $this->policy->undoPublication($user, $blog);

    expect($response->denied())->toBeTrue()
        ->and($response->status())->toBe(404);
});

test('de deleteAny methode retourneert true als de gebruiker de delete_any_blog permissie heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('delete_any_blog')->andReturn(true);

    $result = $this->policy->deleteAny($user);

    expect($result)->toBeTrue();
});

test('de deleteAny methode retourneert false als de gebruiker de delete_any_blog permissie niet heeft', function (): void {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('delete_any_blog')->andReturn(false);

    $result = $this->policy->deleteAny($user);

    expect($result)->toBeFalse();
});
