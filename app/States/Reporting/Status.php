<?php

declare(strict_types=1);

namespace App\States\Reporting;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Represents the lifecycle states of a report.
 *
 * This enum defines three states: "Open," "In Progress," and "Closed." Each state is associated
 * with a label, icon, and color for consistent representation in the user interface.
 * It integrates with Filament's UI contracts and uses the `Comparable` trait for state comparisons.
 *
 * @package App\States\Reporting
 */
enum Status: int implements HasLabel, HasColor, HasIcon
{
    use Comparable;

    /**
     * The "Open" state indicates that the report has been created but has not yet been addressed.
     * Reports in this state are awaiting action and are visible to administrators or moderators for further processing.
     */
    case Open = 1;

    /**
     * The "In Progress" state indicates that the report is currently being handled by an assignee.
     * This state reflects that work is actively being done to resolve the issue described in the report.
     */
    case InProgress = 2;

    /**
     * The "Closed" state indicates that the report has been resolved or addressed.
     * Reports in this state are considered finalized and require no further action.
     */
    case Closed = 3;

    /**
     * Returns the human-readable label for the current state.
     *
     * This method provides a localized label for each state, ensuring that the labels are
     * user-friendly and translated into the application's supported languages. For example,
     * the "Open" state is labeled as "onbehandeld," while the "In Progress" state is labeled
     * as "in behandeling."
     *
     * @return string The translated label for the current state.
     */
    public function getLabel(): string
    {
        $label =  match($this) {
            self::Open => 'onbehandeld',
            self::InProgress => 'in behandeling',
            self::Closed => 'behandeld',
        };

        return trans($label);
    }

    /**
     * Returns the icon identifier for the current state.
     *
     * This method provides an icon identifier for each state, which can be used to display a visual indicator in the user interface.
     * For example, the "Open" state is represented by a dashed circle icon with an "X," while the "Closed" state is represented by a dashed circle icon with a checkmark.
     *
     * @return string The icon identifier for the current state.
     */
    public function getIcon(): string
    {
        return match($this) {
            self::Open => 'tabler-circle-dashed-x',
            self::InProgress => 'tabler-circle-dashed',
            self::Closed => 'tabler-circle-dashed-check',
        };
    }

    /**
     * Returns the color associated with the current state.
     *
     * This method defines a color for each state, which can be used to style the user interface consistently.
     * For example, the "Open" state is associated with the color "danger" (red), the "In Progress" state is associated with the color "warning" (yellow), and the "Closed" state is associated with the color "success" (green).
     *
     * @return string  The color associated with the current state.
     */
    public function getColor(): string
    {
        return match($this) {
            self::Open => 'danger',
            self::InProgress => 'warning',
            self::Closed => 'success',
        };
    }
}
