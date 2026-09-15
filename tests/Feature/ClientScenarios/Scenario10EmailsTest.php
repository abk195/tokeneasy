<?php

namespace Tests\Feature\ClientScenarios;

use App\Admin;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\Support\ScenarioTestCase;

/**
 * Every email the platform can send from a live screen.
 *
 * Nothing is faked: each email goes through Laravel's real mailer with the
 * "array" transport, so the template is fully rendered and the actual message —
 * recipient, sender, subject, body — is inspected. Links inside an email are
 * then followed, so "the reset email works" means the reset actually completes.
 *
 * Whether mail leaves the server depends on the SMTP settings in the server's
 * .env; that is checked on the server with /admin/mailgun/test.
 */
class Scenario10EmailsTest extends ScenarioTestCase
{
    const SENDER = 'no-reply@tokeneasy.io';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.is_demo'       => false,
            'mail.driver'       => 'array',
            'mail.MAIL_STATUS'  => true,
            'mail.from.address' => self::SENDER,
            'mail.from.name'    => 'Token easy',
        ]);
        view()->share('isDemo', false);

        Storage::fake('public');
        Storage::fake('local');
    }

    // ---------------------------------------------------------- registration

    /** @test */
    public function an_investor_gets_a_welcome_email_on_registering()
    {
        $this->post('/register', $this->registration('new.investor@example.test', User::USER_TYPE_INVESTOR));

        $mail = $this->onlyMailTo('new.investor@example.test');

        $this->assertStringStartsWith('Welcome to', $mail->getSubject());
        $this->assertSentFromThePlatform($mail);
    }

    /** @test */
    public function an_issuer_gets_a_welcome_email_on_registering()
    {
        $this->post('/issuer/register', $this->registration('new.issuer@example.test', User::USER_TYPE_ISSUER));

        $mail = $this->onlyMailTo('new.issuer@example.test');

        $this->assertStringStartsWith('Welcome to', $mail->getSubject());
        $this->assertSentFromThePlatform($mail);
    }

    /** @test */
    public function the_verification_link_in_the_welcome_email_verifies_the_account()
    {
        $this->post('/register', $this->registration('verify.me@example.test', User::USER_TYPE_INVESTOR));
        User::where('email', 'verify.me@example.test')->update(['verified' => 0]);

        $link = $this->linkIn($this->onlyMailTo('verify.me@example.test'), '/verifyemail/');
        $this->assertNotNull($link, 'The welcome email has no verification link.');

        $this->get($link)->assertRedirect('/login');

        $this->assertSame(1, (int) User::where('email', 'verify.me@example.test')->value('verified'));
    }

    /** @test */
    public function no_welcome_email_is_sent_when_mail_is_switched_off()
    {
        config(['mail.MAIL_STATUS' => false]);

        $this->post('/register', $this->registration('quiet@example.test', User::USER_TYPE_INVESTOR));

        $this->assertNotNull(User::where('email', 'quiet@example.test')->first(), 'Registration itself failed.');
        $this->assertCount(0, $this->mailTo('quiet@example.test'));
    }

    // ------------------------------------------------------- forgot password

    /** @test */
    public function the_forgot_password_email_lets_an_investor_reset_their_password()
    {
        $investor = $this->makeInvestor(['email' => 'forgetful@example.test']);

        $this->post('/password/email', ['email' => 'forgetful@example.test'])->assertSessionHasNoErrors();

        $mail = $this->onlyMailTo('forgetful@example.test');
        $this->assertSentFromThePlatform($mail);

        $link = $this->linkIn($mail, '/password/reset/');
        $this->assertNotNull($link, 'The reset email has no reset link.');

        $this->get($link)->assertStatus(200);

        $token = basename(parse_url($link, PHP_URL_PATH));
        $reset = $this->post('/password/reset', [
            'token'                 => $token,
            'email'                 => 'forgetful@example.test',
            'password'              => 'N3wPassword!',
            'password_confirmation' => 'N3wPassword!',
        ]);

        $this->assertTrue(Hash::check('N3wPassword!', $investor->fresh()->password), 'The password was not changed by the reset link.');
        $reset->assertRedirect('/home');
    }

    /** @test */
    public function the_forgot_password_email_lets_an_issuer_reset_their_password()
    {
        $issuer = $this->makeIssuer(['email' => 'issuer.forgot@example.test']);

        $this->post('/password/email', ['email' => 'issuer.forgot@example.test'])->assertSessionHasNoErrors();

        $link = $this->linkIn($this->onlyMailTo('issuer.forgot@example.test'), '/password/reset/');
        $this->assertNotNull($link);
        $this->get($link)->assertStatus(200);

        $reset = $this->post('/password/reset', [
            'token'                 => basename(parse_url($link, PHP_URL_PATH)),
            'email'                 => 'issuer.forgot@example.test',
            'password'              => 'N3wPassword!',
            'password_confirmation' => 'N3wPassword!',
        ]);

        $this->assertTrue(Hash::check('N3wPassword!', $issuer->fresh()->password), 'The issuer password was not changed.');

        // /home is investor-only; issuers used to be bounced to the demo token page.
        $reset->assertRedirect('/issuer/dashboard');
    }

    /**
     * The reset page is shared with an older custom flow that passes $user and
     * posts to /check_password. It must keep working for that flow too.
     *
     * @test
     */
    public function the_older_custom_reset_page_still_renders()
    {
        $investor = $this->makeInvestor(['email' => 'legacy@example.test', 'email_token' => 'legacy-token']);

        $response = $this->get('/reset/password/legacy-token');
        $response->assertStatus(200);

        $html = $response->getContent();
        $this->assertContains(url('/check_password'), $html);
        $this->assertContains('value="legacy@example.test"', $html);
        $this->assertContains('name="confirm_password"', $html);
    }

    /** @test */
    public function the_standard_reset_page_posts_the_token_and_confirmation_laravel_expects()
    {
        $html = $this->get('/password/reset/abc123?email=someone%40example.test')->assertStatus(200)->getContent();

        $this->assertContains(url('/password/reset'), $html);
        $this->assertContains('name="token" value="abc123"', $html);
        $this->assertContains('value="someone@example.test"', $html);
        $this->assertContains('name="password_confirmation"', $html);
    }

    /** @test */
    public function the_admin_forgot_password_email_lets_an_admin_reset_their_password()
    {
        $admin = $this->makeAdmin('admin.forgot@example.test');

        $this->post('/admin/password/email', ['email' => 'admin.forgot@example.test'])->assertSessionHasNoErrors();

        $mail = $this->onlyMailTo('admin.forgot@example.test');
        $this->assertSentFromThePlatform($mail);

        $link = $this->linkIn($mail, '/admin/password/reset/');
        $this->assertNotNull($link, 'The admin reset email has no reset link.');
        $this->get($link)->assertStatus(200);

        $this->post('/admin/password/reset', [
            'token'                 => basename(parse_url($link, PHP_URL_PATH)),
            'email'                 => 'admin.forgot@example.test',
            'password'              => 'N3wAdminPass!',
            'password_confirmation' => 'N3wAdminPass!',
        ]);

        $this->assertTrue(Hash::check('N3wAdminPass!', $admin->fresh()->password), 'The admin password was not changed.');
    }

    // ------------------------------------------------------------ contact us

    /** @test */
    public function the_contact_form_emails_support_with_the_visitors_message()
    {
        $this->postJson('/contact/submit', [
            'name'    => 'Layla Haddad',
            'email'   => 'layla@example.test',
            'message' => 'I would like to know more about listing a property.',
        ])->assertJson(['success' => true]);

        $mail = $this->onlyMailTo('support@tokeneasy.io');
        $body = $this->bodyOf($mail);

        $this->assertSentFromThePlatform($mail);
        $this->assertContains('Layla Haddad', $body);
        $this->assertContains('layla@example.test', $body);
        $this->assertContains('I would like to know more about listing a property.', $body);
    }

    // ------------------------------------------------------- withdrawal OTP

    /**
     * @test
     * @dataProvider otpRoutes
     */
    public function a_withdrawal_otp_is_emailed_and_matches_the_one_on_record(string $role, string $path)
    {
        $user = $role === 'issuer' ? $this->makeIssuer() : $this->makeInvestor();

        $this->actingAs($user)->get($path)->assertStatus(200);

        $mail = $this->onlyMailTo($user->email);
        $this->assertSame('Withdrawal OTP', $mail->getSubject());
        $this->assertSentFromThePlatform($mail);

        $this->assertRegExp('/\b(\d{6})\b/', strip_tags($this->bodyOf($mail)), 'The OTP email contains no 6-digit code.');
        preg_match('/\b(\d{6})\b/', strip_tags($this->bodyOf($mail)), $code);

        $this->assertTrue(Hash::check($code[1], $user->fresh()->eth_otp), 'The emailed OTP does not match the one stored.');
    }

    public function otpRoutes(): array
    {
        return [
            'investor' => ['investor', '/generate/withdrawOTP'],
            'issuer'   => ['issuer', '/issuer/generate/withdrawOTP'],
        ];
    }

    // --------------------------------------------------------- admin test page

    /** @test */
    public function the_admin_mail_test_page_sends_a_real_email()
    {
        $admin = $this->makeAdmin('admin@example.test');

        $this->actingAs($admin, 'admin')->get('/admin/mailgun/test')->assertStatus(200);

        $this->actingAs($admin, 'admin')->postJson('/admin/mailgun/send-test-email', [
            'to_email' => 'inbox@example.test',
            'subject'  => 'Delivery check',
            'message'  => 'Checking mail delivery.',
        ])->assertJson(['success' => true]);

        $this->assertSentFromThePlatform($this->onlyMailTo('inbox@example.test'));
    }

    // ----------------------------------------------------------------- setup

    /** @return \Swift_Mime_SimpleMessage[] */
    private function sent(): array
    {
        return app('swift.transport')->driver()->messages()->all();
    }

    private function mailTo(string $address): array
    {
        return array_values(array_filter($this->sent(), function ($message) use ($address) {
            return array_key_exists($address, (array) $message->getTo());
        }));
    }

    private function onlyMailTo(string $address)
    {
        $mail = $this->mailTo($address);
        $this->assertCount(1, $mail, "Expected one email to {$address}; sent to: " . json_encode(array_map(function ($m) {
            return array_keys((array) $m->getTo());
        }, $this->sent())));

        return $mail[0];
    }

    private function assertSentFromThePlatform($mail)
    {
        $this->assertSame([self::SENDER], array_keys((array) $mail->getFrom()), 'This email does not use the configured sender address.');
    }

    private function bodyOf($mail): string
    {
        $body = (string) $mail->getBody();
        foreach ($mail->getChildren() as $part) {
            $body .= "\n" . $part->getBody();
        }

        return html_entity_decode($body);
    }

    private function linkIn($mail, string $pathFragment)
    {
        preg_match_all('#https?://[^\s"\'<>]+#', $this->bodyOf($mail), $urls);
        foreach ($urls[0] as $url) {
            if (strpos($url, $pathFragment) !== false) {
                return parse_url($url, PHP_URL_PATH) . (parse_url($url, PHP_URL_QUERY) ? '?' . parse_url($url, PHP_URL_QUERY) : '');
            }
        }

        return null;
    }

    private function makeAdmin(string $email): Admin
    {
        $admin = new Admin();
        $admin->name           = 'Platform Admin';
        $admin->email          = $email;
        $admin->password       = bcrypt('OldAdminPass!');
        $admin->picture        = '';
        $admin->wallet_address = '';
        $admin->save();

        return $admin;
    }

    private function registration(string $email, int $type): array
    {
        return [
            'name'                  => 'New Person',
            'email'                 => $email,
            'password'              => 'Passw0rd!',
            'password_confirmation' => 'Passw0rd!',
            'user_type'             => $type,
            'country_id'            => 1,
            'account_type'          => 'individual',
            'issuer_pros_doc'       => UploadedFile::fake()->create('id.pdf', 100, 'application/pdf'),
            'issuer_kyc_doc'        => UploadedFile::fake()->create('address.pdf', 100, 'application/pdf'),
            'list1' => 'on', 'list2' => 'on', 'list3' => 'on', 'list4' => 'on',
        ];
    }
}
