<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\AuthenticationEvents;
use App\Models\AuthenticationLog;
use Illuminate\Events\Dispatcher;
use Illuminate\Auth\Events;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Fortify\Events as FortifyEvents;

final readonly class AuthSubscriber
{
    protected function logEvent(AuthenticationEvents $eventType, object $event, array $context = []): void
    {
        $userId = null;
        $guard = $event->guard ?? null;

        if (isset($event->user) && $event->user instanceof Authenticatable) {
            $userId = optional($event->user)->id;
        }

        AuthenticationLog::query()->create([
            'user_id'    => $userId,
            'event' =>  $eventType,
            'guard'      => $guard,
            'message'    => $eventType->getDescription(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'context'    => $context,
        ]);
    }

    public function handleAttempting(Events\Attempting $event): void
    {
        $this->logEvent(
            eventType: AuthenticationEvents::Attempting,
            event: $event,
            context:  [
                'attempted_email' => $event->credentials['email'],
                'login_type' => 'Attempting',
            ]);
    }

    public function handleFailed(Events\Failed $event): void
    {
        $this->logEvent(
            eventType: AuthenticationEvents::Failed,
            event: $event,
            context: [
                'attempted_email' => $event->credentials['email'],
                'user_found' => $event->user ? '0' : '1',
            ]
        );
    }

    public function handleLogin(Events\Login $event): void
    {
        $this->logEvent(
            eventType: AuthenticationEvents::Login,
            event: $event,
            context: ['remember_me' => $event->remember ? '0': '1'],
        );
    }

    public function handleLogout(Events\Logout $event): void
    {
        $this->logEvent(
            eventType: AuthenticationEvents::Logout,
            event: $event,
            context: ['logout_type' => 'Gebruiker heeft handmatig uitgelogd.'],
        );
    }

    public function handleLockout(Events\Lockout $event): void
    {
        $this->logEvent(
            eventType: AuthenticationEvents::Lockout,
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
            eventType: AuthenticationEvents::CurrentDeviceLogout,
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
            eventType: AuthenticationEvents::OtherDeviceLogout,
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
            eventType: AuthenticationEvents::Registered,
            event: $event,
            context: ['new_user_id' => $event->user->id ?? 'N/A',]
        );
    }

    public function handleVerified(Events\Verified $event): void
    {
        $this->logEvent(
            eventType:AuthenticationEvents::Verified,
            event: $event,
            context: ['verification_time' => now()->toDateTimeString()]
        );
    }

    public function handlePasswordReset(Events\PasswordReset $event): void
    {
        $this->logEvent(
            eventType: AuthenticationEvents::PasswordReset,
            event: $event,
            context: ['reset_method' => 'Via wachtwoord reset mechanisme.'],
        );
    }

    public function handlePasswordUpdatedViaController(FortifyEvents\PasswordUpdatedViaController $event): void
    {
        $this->logEvent(
            eventType: AuthenticationEvents::PasswordUpdatedViaController,
            event: $event,
        );
    }

    public function handleRecoveryCodeReplaced(FortifyEvents\RecoveryCodeReplaced $event): void
    {
        $this->logEvent(
            eventType: AuthenticationEvents::RecoveryCodeReplaced,
            event: $event,
        );
    }

    public function handleRecoveryCodesGenerated(FortifyEvents\RecoveryCodesGenerated $event): void
    {
        $this->logEvent(
            eventType: AuthenticationEvents::RecoveryCodesGenerated,
            event: $event,
        );
    }

    public function handleTwoFactorAuthenticationChallenged(FortifyEvents\TwoFactorAuthenticationChallenged $event): void
    {
        $this->logEvent(
            eventType: AuthenticationEvents::TwoFactorAuthenticationChallenged,
            event: $event,
        );
    }

    public function handleTwoFactorAuthenticationConfirmed(FortifyEvents\TwoFactorAuthenticationConfirmed $event): void
    {
        $this->logEvent(
            eventType: AuthenticationEvents::TwoFactorAuthenticationConfirmed,
            event: $event,
        );
    }

    public function handleTwoFactorAuthenticationDisabled(FortifyEvents\TwoFactorAuthenticationDisabled $event): void
    {
        $this->logEvent(
            eventType: AuthenticationEvents::TwoFactorAuthenticationDisabled,
            event: $event,
        );
    }

    public function handleTwoFactorAuthenticationEnabled(FortifyEvents\TwoFactorAuthenticationEnabled $event): void
    {
        $this->logEvent(
            eventType: AuthenticationEvents::TwoFactorAuthenticationEnabled,
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
