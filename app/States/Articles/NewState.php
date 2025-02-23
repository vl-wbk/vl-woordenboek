<?php

declare(strict_types=1);

namespace App\States\Articles;

/**
 * No transitions embedded for now because on the first edit the state will automatically transition to Draft
 */
final class NewState extends ArticleState {}
