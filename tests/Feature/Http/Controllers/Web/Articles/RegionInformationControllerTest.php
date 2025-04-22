<?php

use function Pest\Laravel\get;

test('It can successfully display the region information page', function (): void {
    get(route('definitions.region-info'))->assertSuccessful();
});
