@props(['bag' => null, 'field' => null])

@error($field, $bag)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror
