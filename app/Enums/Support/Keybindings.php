<?php

declare(strict_types=1);

namespace App\Enums\Support;

enum Keybindings: string
{
    case EditArticle = 'meta+option+e';
    case UndoPublication = 'meta+option+u';
    case ArchivePublication = 'meta+option+a';
    case AcceptPublication = 'meta+option+p';
    case RejectPublication = 'meta+option+r';
    case DeletePublication = 'meta+option+d';

    public function policyMethod(): string
    {
        return match ($this) {
            self::EditArticle => 'update',
            self::UndoPublication => 'unpublish',
            self::ArchivePublication => 'archiveArticle',
            self::DeletePublication => 'delete',
            self::AcceptPublication, self::RejectPublication => 'publish',

        };
    }

    public function domId(): string
    {
        return match ($this) {
            self::EditArticle => 'editArticle',
            self::UndoPublication => 'undoPublication',
            self::ArchivePublication => 'archivePublication',
            self::AcceptPublication => 'acceptPublication',
            self::RejectPublication => 'rejectPublication',
            self::DeletePublication => 'deleteArticle',
        };
    }
}
