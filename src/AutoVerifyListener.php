<?php

namespace LinkRobins\AutoVerify;

use Flarum\User\Event\Saving;

class AutoVerifyListener
{
    public function handle(Saving $event): void
    {
        if (!$event->user->exists) {
            $event->user->activate();
        }
    }
}
