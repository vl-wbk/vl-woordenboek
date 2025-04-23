<?php

use App\Models\Article;
use App\Models\User;
use App\States\Reporting\Status;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

/**
 * Tests that an unauthenticated (guest) user cannot submit a report for an article.
 * This test ensures that when a user who is not logged in attempts to create an article report, they are redirected to the login page, as this action requires authentication.
 */
test('An unauthenticated user cannot store a article report in the application', function (): void {
    // Arrange: Create an article for the report. No user authentication is set up.
    $article = Article::factory()->create();

    // Act: Simulate an unauthenticated user attempting to post a report for the article.
    post(route('article-report.create', $article), ['melding' => fake()->sentence()])
        ->assertRedirect(route('login'));
});

/**
 * Tests that an authenticated user can successfully submit a report for a specific document.
 * This test ensures that when a logged-in user submits a report with a description,
 * the report is stored in the database with the correct details, a success message is displayed,
 * and the user is redirected back to the document's information page.
 */
test('An authenticated user can sucessfully store a article report in the application', function (): void {
    // Arrange: Set up a document and an authenticated user.
    $article = Article::factory()->create();
    $user = User::factory()->create();

    // Act: Simulate an authenticated user submitting a document report.
    actingAs($user)
        ->post(route('article-report.create', $article), data: ['melding' => $description = fake()->sentence()])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status', trans('We hebben uw melding goed ontvangen!'))
        ->assertRedirect(route('word-information.show', ['word' => $article]));

    // Assert: Check if the report was stored correctly in the database.
    $this->assertDatabaseHas('article_reports', [
        'description' => $description,
        'state' => Status::Open,
        'author_id' => $user->id,
        'article_id' => $article->id,
    ]);
});

/**
 * Tests that the 'melding' field (description) is a mandatory requirement when submitting a document report.
 * This test verifies that if a user attempts to submit a report without providing a description,
 * a validation error is triggered, preventing the report from being stored.
 */
test('That the description of the report is required', function (): void {
    // Arrange: Set up a dictionary article and an authenticated user.
    $article = Article::factory()->create();
    $user = User::factory()->create();

    // Act: Simulate an authenticated user submitting a document report without a description.
    actingAs($user)
        ->post(route('article-report.create', $article))
        ->assertSessionHasErrors('melding');
});
