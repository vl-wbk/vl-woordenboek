@if ($article)
    <li class="nav-item">
        <a href="{{ route('word-information.show', $article) }}" class="nav-link">
            <x-heroicon-o-document-magnifying-glass class="icon me-1"/> Snuisteren
        </a>
    </li>
@endif