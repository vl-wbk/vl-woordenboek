<?php

// config/syllables.php

use App\UserTypes;

return [
    'auto_approve'  => true,
    'min_votes'     => 3,
    'min_margin'    => 2,
    // roles whose suggestions are published immediately (Spatie roles); adjust to your role names
    'trusted_roles' => [UserTypes::Administrators->value, UserTypes::Editor->value, UserTypes::EditorInChief->value, UserTypes::Developer->value],
];
