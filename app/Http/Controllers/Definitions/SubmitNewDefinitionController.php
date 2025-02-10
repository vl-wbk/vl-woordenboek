<?php

declare(strict_types=1);

namespace App\Http\Controllers\Definitions;

use App\Actions\Definitions\StoreDefinition;
use App\Http\Controllers\Controller;
use App\Http\Requests\Definitions\StoreDefinitionsRequest;
use App\Models\Region;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final readonly class SubmitNewDefinitionController
{
    public function create(): Renderable
    {
        return view('definitions.create', [
            'regions' => Region::query()->pluck('name', 'id')
        ]);
    }


    /**
     * @todo Implementeren van een flash message om de gebruiker te laten weten/bedanken dat zijn suggestie is opgenomen en word behandeld.
     */
    public function store(StoreDefinitionsRequest $storeDefinitionsRequest, StoreDefinition $storeDefinition): RedirectResponse
    {
        $storeDefinition->execute($storeDefinitionsRequest->getData());

        return back();
    }
}
