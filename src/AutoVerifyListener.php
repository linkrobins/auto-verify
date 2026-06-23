<?php

namespace LinkRobins\AutoVerify;

use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\Event\Saving;

class AutoVerifyListener
{
    public function __construct(
        protected SettingsRepositoryInterface $settings
    ) {
    }

    public function handle(Saving $event): void
    {
        if ($event->user->exists) {
            return;
        }

        // Admin kill-switch: when auto-verify is turned off, do nothing and let
        // Flarum's normal email-confirmation flow take over (useful during a
        // spam wave) without disabling the whole extension. Defaults to on.
        if (! (bool) $this->settings->get('linkrobins-auto-verify.enabled', true)) {
            return;
        }

        $event->user->activate();
    }
}
