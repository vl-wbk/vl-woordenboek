<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;

enum AuthenticationEvents: int implements HasLabel, HasDescription
{
    case Attempting = 1;
    case Failed = 2;
    case Login = 3;
    case Logout = 4;
    case Lockout = 5;
    case CurrentDeviceLogout = 6;
    case OtherDeviceLogout = 7;
    case Registered = 8;
    case Verified = 9;
    case PasswordReset = 10;
    case PasswordUpdatedViaController = 11;
    case RecoveryCodeReplaced = 12;
    case RecoveryCodesGenerated = 13;
    case TwoFactorAuthenticationChallenged = 14;
    case TwoFactorAuthenticationConfirmed = 15;
    case TwoFactorAuthenticationEnabled = 16;
    case TwoFactorAuthenticationDisabled = 17;

    public function getLabel(): string
    {
        return match ($this) {
            self::Attempting => 'auth:poging',
            self::Failed => 'auth:mislukt',
            self::Login => 'auth:succes',
            self::Logout => 'auth:logout',
            self::Lockout => 'auth:lockout',
            self::CurrentDeviceLogout => 'auth:logout-huidig-apparaat',
            self::OtherDeviceLogout => 'auth:logout-ander-apparaat',
            self::Registered => 'account:registered',
            self::Verified => 'account:verified',
            self::PasswordReset => 'account:password-reset',
            self::PasswordUpdatedViaController => 'account:password-updated',
            self::RecoveryCodeReplaced => '2fa:recovery-code-replaced',
            self::RecoveryCodesGenerated => '2fa:recovery-codes-generated',
            self::TwoFactorAuthenticationChallenged => '2fa:challenged',
            self::TwoFactorAuthenticationConfirmed => '2fa:challenge-confirmed',
            self::TwoFactorAuthenticationEnabled => '2fa:enabled',
            self::TwoFactorAuthenticationDisabled => '2fa:disabled',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::Attempting => 'Inlogpoging gestart',
            self::Failed => 'Inlogpoging mislukt',
            self::Login => 'Gebruiker succesvol ingelogd',
            self::Logout => 'Gebruiker succesvol uitgelogd',
            self::Lockout => 'Account/IP is geblokkeerd vanwege te veel mislukte pogingen.',
            self::CurrentDeviceLogout => 'Uitgelogd van de huidige sessie.',
            self::OtherDeviceLogout => 'Uitgelogd van andere apparaten/sessies.',
            self::Registered => 'Nieuwe gebruiker succesvol geregistreerd.',
            self::Verified => 'Email adres succesvol geverifieerd.',
            self::PasswordReset => 'Wachtwoord succesvol gereset.',
            self::PasswordUpdatedViaController => 'Wachtwoord bijgewerkt via de controller. (profiel instellingen).)',
            self::RecoveryCodeReplaced => 'Een herstelcode is vervangen',
            self::RecoveryCodesGenerated => 'Nieuwe herstelcodes zijn gegenereerd.',
            self::TwoFactorAuthenticationChallenged => 'Uitgedaagd voor Twee-Factor Authenticatie (TFA).',
            self::TwoFactorAuthenticationConfirmed => 'Twee-Factor Authenticatie (TFA) is succesvol bevestigd.',
            self::TwoFactorAuthenticationEnabled => 'Twee-Factor Authenticatie (TFA) is ingeschakeld.',
            self::TwoFactorAuthenticationDisabled => 'Twee-Factor Authenticatie (TFA) is uitgeschakeld.',
        };
    }
}
