<?php

declare(strict_types=1);

namespace App\Actions\Definitions;

use App\Data\DefinitionDataObject;
use App\Models\Suggestion;
use Illuminate\Support\Facades\DB;

final readonly class StoreDefinition
{
    public function execute(DefinitionDataObject $definitionDataObject): void
    {
        DB::transaction(function () use ($definitionDataObject): void {
            $suggestion = Suggestion::query()->create($definitionDataObject->except('regions')->toArray());
            $suggestion->regions()->sync($definitionDataObject->regions);

            if (! is_null($definitionDataObject->creator_id)) {
                $suggestion->creator()->associate($definitionDataObject->creator_id)->save();
            }
        });
    }
}
