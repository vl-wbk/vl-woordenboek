<?php

test('It can successfully display the terms of service page', function (): void {
    $this->get(route('terms-of-service'))->assertSuccessful();
});
