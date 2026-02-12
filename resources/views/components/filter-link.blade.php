@props(['field', 'value'])

@php
    $currentFilters = request('filter', []);
    // Check if this specific filter (field=value) is currently active
    $isActive = isset($currentFilters[$field]) && $currentFilters[$field] == $value;

    // Build the next set of filters
    $nextFilters = $currentFilters;

    if ($isActive) {
        // If active, clicking the link removes the filter for this field
        unset($nextFilters[$field]);
    } else {
        // If not active, clicking the link applies the filter (field=value)
        $nextFilters[$field] = $value;
    }

    // Spatie uses the 'filter' key for all filters
    // We pass the new filters array to fullUrlWithQuery
    $query = ['filter' => $nextFilters];
    $query['page'] = null;
@endphp

<div class="form-check form-switch">
    <input class="form-check-input" value="{{ urldecode(request()->fullUrlWithQuery($query)) }}" type="checkbox" id="flexSwitchCheckChecked" onchange="location.href=this.value"
        @checked($isActive)>
    <label class="form-check-label" for="flexSwitchCheckChecked">{{ $slot }}</label>
</div>
