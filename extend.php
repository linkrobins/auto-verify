<?php

use Flarum\Extend;
use Flarum\User\Event\Saving;
use LinkRobins\AutoVerify\AutoVerifyListener;

return [
    (new Extend\Event)
        ->listen(Saving::class, AutoVerifyListener::class),
];
