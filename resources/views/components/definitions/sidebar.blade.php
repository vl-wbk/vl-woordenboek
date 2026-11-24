@props(['word'])

<x-definitions.sidebar.details :word="$word"/>
<x-definitions.sidebar.labels :word="$word"/>
<x-definitions.sidebar.region-map :word="$word"/>

<div class="mt-3">
    <x-thema-list-component :word="$word"/>
</div>

<x-definitions.sidebar.actions :word="$word"/>
