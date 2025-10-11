<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\AuthenticationLog;
use Illuminate\Events\Dispatcher;
use Illuminate\Auth\Events;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Fortify\Events as FortifyEvents;

final readonly class AuthSubscriber
{
    protected function logEvent(string $eventType, string $message, object $event, array $context = []): void
    {
        $userId = null;
        $guard = $event->guard ?? null;

        if (isset($event->user) && $event->user instanceof Authenticatable) {
            $userId = optional($event->user)->id;
        }

        AuthenticationLog::query()->create([
            'user_id'    => $userId,
            'event' => $eventType,
            'guard'      => $guard,
            'message'    => $message,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'context'    => $context,
        ]);
    }

    public function handleAttempting(Events\Attempting $event): void
    {
        $this->logEvent(
            eventType: 'Attempting',
            message: 'Inlogpoging gestart.',
            event: $event,
            context:  [
                'credentials' => array_filter($event->credentials, fn($key) => $key !== 'password', ARRAY_FILTER_USE_KEY),
                'login_type' => 'Attempting',
            ]);
    }

    public function handleFailed(Events\Failed $event): void
    {
        $this->logEvent(
            eventType: 'Failed',
            message: 'Inlogpoging mislukt.',
            event: $event,
            context: [
                'credentials' => array_filter($event->credentials, fn($key) => $key !== 'password', ARRAY_FILTER_USE_KEY),
                'user_found' => (bool) $event->user,
            ]
        );
    }

    public function handleLogin(Events\Login $event): void
    {
        $this->logEvent(
            eventType: 'Login',
            message: 'Gebruiker succesvol ingelogd.',
            event: $event,
            context: ['remember_me' => $event->remember]
        );
    }

    public function handleLogout(Events\Logout $event): void
    {
        $this->logEvent(
            eventType: 'Logout',
            message: 'Gebruiker succesvol uitgelogd.',
            event: $event,
            context: ['logout_type' => 'Gebruiker heeft handmatig uitgelogd.'],
        );
    }

    public function handleLockout(Events\Lockout $event): void
    {
        $this->logEvent(
            eventType: 'Lockout',
            message: 'Account/IP is geblokkeerd vanwege te veel mislukte pogingen.',
            event: $event,
            context: [
                'lockout_at' => now()->toDateTimeString(),
                'login_id_attempted' => request()->input('email') ?? 'Onbekend',
            ],
        );
    }

    public function handleCurrentDeviceLogout(Events\CurrentDeviceLogout $event): void
    {
        $this->logEvent(
            eventType: 'CurrentDeviceLogout',
            message: 'Uitgelogd van de huidige sessie.',
            event: $event,
            context: [
                'action' => 'Huidige sessie is beëindigd.',
                'guard' => $event->guard,
            ],
        );
    }

    public function handleOtherDeviceLogout(Events\OtherDeviceLogout $event): void
    {
        $this->logEvent(
            eventType: 'OtherDeviceLogout',
            message: 'Uitgelogd van andere apparaten/sessies.',
            event: $event,
            context: [
                'action' => 'Alle andere sessies beëindigd.',
                'initiator_guard' => $event->guard,
            ],
        );
    }

    public function handleRegistered(Events\Registered $event): void
    {
        $this->logEvent(
            eventType: 'Registered',
            message: 'Nieuwe gebruiker succesvol geregistreerd.',
            event: $event,
            context: ['new_user_id' => $event->user->id ?? 'N/A',]
        );
    }

    public function handleVerified(Events\Verified $event): void
    {
        $this->logEvent(
            eventType:'Verified',
            message: 'E-mailadres succesvol geverifieerd.',
            event: $event,
            context: ['verification_time' => now()->toDateTimeString()]
        );
    }

    public function handlePasswordReset(Events\PasswordReset $event): void
    {
        $this->logEvent(
            eventType: 'PasswordReset',
            message: 'Wachtwoord succesvol gereset.',
            event: $event,
            context: ['reset_method' => 'Via wachtwoord reset mechanisme.'],
        );
    }

    public function handlePasswordUpdatedViaController(FortifyEvents\PasswordUpdatedViaController $event): void
    {
        $this->logEvent(
            eventType: 'PasswordUpdatedViaController',
            message: 'Wachtwoord bijgewerkt via de controller. (profiel instellingen).)',
            event: $event,
        );
    }

    public function handleRecoveryCodeReplaced(FortifyEvents\RecoveryCodeReplaced $event): void
    {
        $this->logEvent(
            eventType: 'RecoveryCodeReplaced',
            message: 'Een herstelcode is vervangen.',
            event: $event,
        );
    }

    public function handleRecoveryCodesGenerated(FortifyEvents\RecoveryCodesGenerated $event): void
    {
        $this->logEvent(
            eventType: 'RecoveryCodesGenerated',
            message: 'Nieuwe herstelcodes zijn gegenereerd.',
            event: $event,
        );
    }

    public function handleTwoFactorAuthenticationChallenged(FortifyEvents\TwoFactorAuthenticationChallenged $event): void
    {
        $this->logEvent(
            eventType: 'TwoFactorAuthenticationChallenged',
            message: 'Uitgedaagd voor Twee-Factor Authenticatie (TFA).',
            event: $event,
        );
    }

    public function handleTwoFactorAuthenticationConfirmed(FortifyEvents\TwoFactorAuthenticationConfirmed $event): void
    {
        $this->logEvent(
            eventType: 'TwoFactorAuthenticationConfirmed',
            message: 'Twee-Factor Authenticatie (TFA) is succesvol bevestigd.',
            event: $event,
        );
    }

    public function handleTwoFactorAuthenticationDisabled(FortifyEvents\TwoFactorAuthenticationDisabled $event): void
    {
        $this->logEvent(
            eventType: 'TwoFactorAuthenticationDisabled',
            message: 'Twee-Factor Authenticatie (TFA) is uitgeschakeld.',
            event: $event,
        );
    }

    public function handleTwoFactorAuthenticationEnabled(FortifyEvents\TwoFactorAuthenticationEnabled $event): void
    {
        $this->logEvent(
            eventType: 'TwoFactorAuthenticationEnabled',
            message: 'Twee-Factor Authenticatie (TFA) is ingeschakeld.',
            event: $event,
        );
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            Events\Attempting::class => 'handleAttempting',
            Events\Failed::class => 'handleFailed',
            Events\Login::class => 'handleLogin',
            Events\Logout::class => 'handleLogout',
            Events\Lockout::class => 'handleLockout',
            Events\CurrentDeviceLogout::class => 'handleCurrentDeviceLogout',
            Events\OtherDeviceLogout::class => 'handleOtherDeviceLogout',
            Events\Registered::class => 'handleRegistered',
            Events\Verified::class => 'handleVerified',
            Events\PasswordReset::class => 'handlePasswordReset',
            FortifyEvents\PasswordUpdatedViaController::class => 'handlePasswordUpdatedViaController',
            FortifyEvents\RecoveryCodeReplaced::class => 'handleRecoveryCodeReplaced',
            FortifyEvents\RecoveryCodesGenerated::class => 'handleRecoveryCodesGenerated',
            FortifyEvents\TwoFactorAuthenticationChallenged::class => 'handleTwoFactorAuthenticationChallenged',
            FortifyEvents\TwoFactorAuthenticationConfirmed::class => 'handleTwoFactorAuthenticationConfirmed',
            FortifyEvents\TwoFactorAuthenticationDisabled::class => 'handleTwoFactorAuthenticationDisabled',
            FortifyEvents\TwoFactorAuthenticationEnabled::class => 'handleTwoFactorAuthenticationEnabled',
        ];
    }
}
