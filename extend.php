<?php

use Flarum\Extend;
use Flarum\User\Event\Saving;
use LinkRobins\AutoVerify\AutoVerifyListener;

return [
    (new Extend\Event)
        ->listen(Saving::class, AutoVerifyListener::class),

    (new Extend\Frontend('admin'))
        ->js(__DIR__ . '/js/dist/admin.js'),

    new Extend\Locales(__DIR__ . '/locale'),

    (new Extend\Settings())
        ->default('linkrobins-auto-verify.enabled', true),
];
