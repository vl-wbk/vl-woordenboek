<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

use function PHPSTORM_META\map;

enum ArticleStatusses: int implements HasLabel
{
    /**
     * NEW (SUGGESTIE)
     *
     * A registered contributor can submit a suggestion for a new article using the designated form.
     * The article automatically receives the processing status NEW.
     */
    case New = 0;

    /**
     * EDITING (REDACTIE)^
     *
     * When an editor starts working on an article with a processing status, it is automatically assigned the status EDITING.
     * The editor manually updates the status to APPROVAL once they have completed the editorial work.
     */
    case Redaction = 1;

    /**
     * APPROVAL (GOEDEGEKEURD)
     *
     * A senior editor reviews the article and decides whether to make it visible to the public by changing the status to RELEASED.
     * Alternatively, they can return it to EDITING for further modifications or archive it by setting it to PASSIVE.
     */
    case Approval = 2;

    /**
     * RELEASED (ONTSLUITING)
     * Once an article has the status RELEASED, it becomes publicly visible.
     * In this state, a senior editor can still make changes or choose to archive it by setting its status to PASSIVE.
     */
    case Released = 3;

    /**
     * PASSIVE (PASSIEF)
     *
     * All articles with the PASSIVE status are archived and no longer visible to the public.
     * A senior editor can reactivate a PASSIVE article by changing its status back to EDITING.
     */
    case Passive = 4;

    public function getLabel(): string
    {
        return match ($this) {
            self::New => 'suggestie',
            self::Redaction => 'Redactie',
            self::Approval => 'Goedgekeurd',
            self::Released => 'Ontsluiting',
            self::Passive => 'Passief',
        };
    }
}
