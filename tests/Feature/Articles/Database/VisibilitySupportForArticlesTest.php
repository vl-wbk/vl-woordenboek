<?php

use App\Models\Article;

beforeEach(function (): void {
    $this->article = Article::factory()->create(['published_at' => now()]);
    $this->hiddenArticle = Article::factory()->create();
});

test('determine whether the is published check is working properly for the artticle', function (): void {
    expect($this->article->isHidden())->toBeFalse();
    expect($this->article->isPublished())->toBeTrue();
});

test('determine whether the is hidden check is working properly for the article', function (): void {
    expect($this->hiddenArticle->isHidden())->toBeTrue();
    expect($this->hiddenArticle->isPublished())->toBe(false);
});
