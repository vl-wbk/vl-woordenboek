<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\CorrectionProposals\Pages;

use App\Filament\Clusters\Articles\Resources\CorrectionProposals\CorrectionProposalResource;
use Filament\Resources\Pages\ListRecords;

final class ListCorrectionProposals extends ListRecords
{
    protected static string $resource = CorrectionProposalResource::class;
}
