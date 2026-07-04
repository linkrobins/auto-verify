<?php

/*
 * This file is part of linkrobins/auto-verify.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace LinkRobins\AutoVerify\Tests\integration\api;

use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;
use PHPUnit\Framework\Attributes\Test;

class RegistrationTest extends TestCase
{
    use RetrievesAuthorizedUsers;

    public function setUp(): void
    {
        parent::setUp();

        $this->extension('linkrobins-auto-verify');
    }

    private function register(): \Psr\Http\Message\ResponseInterface
    {
        // Guest signup goes through the session CSRF flow, so fetch a token
        // first like the real signup form does.
        return $this->send(
            $this->requestWithCsrfToken(
                $this->request('POST', '/api/users', [
                    'json' => [
                        'data' => [
                            'attributes' => [
                                'username' => 'newmember',
                                'email' => 'newmember@machine.local',
                                'password' => 'a-strong-password',
                            ],
                        ],
                    ],
                ])
            )
        );
    }

    #[Test]
    public function registration_activates_the_account_immediately(): void
    {
        $response = $this->register();

        $this->assertEquals(201, $response->getStatusCode());

        $user = $this->database()->table('users')->where('username', 'newmember')->first();
        $this->assertNotNull($user);
        $this->assertEquals(1, $user->is_email_confirmed);
    }

    #[Test]
    public function the_kill_switch_restores_normal_email_confirmation(): void
    {
        $this->setting('linkrobins-auto-verify.enabled', '0');

        $response = $this->register();

        $this->assertEquals(201, $response->getStatusCode());

        $user = $this->database()->table('users')->where('username', 'newmember')->first();
        $this->assertNotNull($user);
        $this->assertEquals(0, $user->is_email_confirmed);
    }

    #[Test]
    public function editing_an_existing_unconfirmed_user_does_not_activate_them(): void
    {
        $this->prepareDatabase([
            'users' => [
                array_merge($this->normalUser(), ['is_email_confirmed' => 0]), // id 2
            ],
        ]);

        $response = $this->send(
            $this->request('PATCH', '/api/users/2', [
                'authenticatedAs' => 1,
                'json' => [
                    'data' => [
                        'type' => 'users',
                        'id' => '2',
                        'attributes' => ['username' => 'renamed'],
                    ],
                ],
            ])
        );

        $this->assertEquals(200, $response->getStatusCode());

        $user = $this->database()->table('users')->where('id', 2)->first();
        $this->assertEquals('renamed', $user->username);
        // The listener only fires for brand-new accounts; an admin edit must
        // not sneak an unconfirmed user past email confirmation.
        $this->assertEquals(0, $user->is_email_confirmed);
    }
}
