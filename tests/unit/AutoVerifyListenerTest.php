<?php

/*
 * This file is part of linkrobins/auto-verify.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace LinkRobins\AutoVerify\Tests\unit;

use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\Event\Saving;
use Flarum\User\User;
use LinkRobins\AutoVerify\AutoVerifyListener;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPUnit\Framework\Attributes\Test;

class AutoVerifyListenerTest extends MockeryTestCase
{
    private function listener(mixed $enabled): AutoVerifyListener
    {
        $settings = m::mock(SettingsRepositoryInterface::class);
        $settings->shouldReceive('get')
            ->with('linkrobins-auto-verify.enabled', true)
            ->andReturn($enabled);

        return new AutoVerifyListener($settings);
    }

    private function saving(User $user): Saving
    {
        return new Saving($user, new User(), []);
    }

    #[Test]
    public function new_users_are_activated(): void
    {
        $user = new User();

        $this->listener(enabled: true)->handle($this->saving($user));

        $this->assertTrue((bool) $user->is_email_confirmed);
    }

    #[Test]
    public function existing_users_are_left_alone(): void
    {
        $user = new User();
        $user->exists = true;

        $this->listener(enabled: true)->handle($this->saving($user));

        $this->assertFalse((bool) $user->is_email_confirmed);
    }

    #[Test]
    public function the_kill_switch_disables_activation(): void
    {
        $user = new User();

        // The admin toggle stores '0'; the listener must treat it as off.
        $this->listener(enabled: '0')->handle($this->saving($user));

        $this->assertFalse((bool) $user->is_email_confirmed);
    }
}
