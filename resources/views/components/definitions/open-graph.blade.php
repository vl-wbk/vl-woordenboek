@props(['word'])

@php
    $publishedAt = $word->published_at ?? \Illuminate\Support\Carbon::parse($word->created_at);
    $modifiedAt  = $word->updated_at ?? \Illuminate\Support\Carbon::now();
@endphp

<meta property="og:title" content="{{ $word->word }} - {{ config('app.name', 'Laravel') }}"/>
<meta property="og:type" content="article"/>
<meta property="og:url" content="{{ request()->fullUrl() }}"/>
<meta property="og:description" content="{{ $word->seo_description }}"/>
<meta property="og:image" content="{{ asset('/img/app-logo.jpg') }}"/>
<meta property="og:image:alt" content="Logo van het Vlaams woordenboek"/>
<meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}"/>
<meta property="article:published_time" content="{{ $publishedAt->toIso8601String() }}"/>
<meta property="article:modified_time" content="{{ $modifiedAt->toIso8601String() }}"/>
<meta property="og:article:author" content="{{ $word->editor->name ?? '' }}"/>
<meta property="og:section" content="Linguïstiek"/>

@if ($word->isArchived())
    <meta name="robots" content="noindex, follow"/>
@endif
