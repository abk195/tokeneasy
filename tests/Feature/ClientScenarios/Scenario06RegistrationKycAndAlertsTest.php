<?php

namespace Tests\Feature\ClientScenarios;

use App\AccreditedDocument;
use App\AccreditedKycDocument;
use App\Admin;
use App\Document;
use App\KycDocument;
use App\Notification;
use App\Services\TokenPaymentService;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Tests\Support\ScenarioTestCase;

/**
 * SCENARIO 6 — "Test both investor and issuer registrations are working, admin
 * can review their KYC and documents and approve them. Test emails are working
 * and alerts are being generated both on investor and issuer dashboard."
 */
class Scenario06RegistrationKycAndAlertsTest extends ScenarioTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake('local');
    }

    // --------------------------------------------------------- registration

    /** @test */
    public function an_investor_can_register()
    {
        Mail::fake();

        $response = $this->post('/register', $this->registrationPayload([
            'email'     => 'new.investor@example.test',
            'user_type' => User::USER_TYPE_INVESTOR,
        ]));

        $response->assertRedirect('/login');

        $user = User::where('email', 'new.investor@example.test')->first();

        $this->assertNotNull($user, 'The investor account was not created.');
        $this->assertSame(User::USER_TYPE_INVESTOR, (int) $user->user_type);
        $this->assertSame(base64_encode('new.investor@example.test'), $user->email_token);
    }

    /** @test */
    public function an_issuer_can_register_and_is_usable_without_admin_approval()
    {
        Mail::fake();

        $response = $this->post('/issuer/register', $this->registrationPayload([
            'email'     => 'new.issuer@example.test',
            'user_type' => User::USER_TYPE_ISSUER,
        ]));

        $response->assertRedirect('/issuer/register');

        $user = User::where('email', 'new.issuer@example.test')->first();

        $this->assertNotNull($user, 'The issuer account was not created.');
        $this->assertSame(User::USER_TYPE_ISSUER, (int) $user->user_type);
        $this->assertSame(1, (int) $user->approved, 'The issuer cannot proceed without admin approval.');
    }

    /** @test */
    public function registration_rejects_a_weak_password_and_a_duplicate_email()
    {
        $existing = $this->makeInvestor(['email' => 'taken@example.test']);

        $weak = $this->post('/register', $this->registrationPayload([
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]));
        $weak->assertSessionHasErrors('password');

        $duplicate = $this->post('/register', $this->registrationPayload(['email' => 'taken@example.test']));
        $duplicate->assertSessionHasErrors('email');

        $this->assertSame(1, User::where('email', 'taken@example.test')->count());
    }

    /** @test */
    public function a_welcome_email_is_sent_on_registration()
    {
        Mail::fake();
        config(['mail.MAIL_STATUS' => true]);

        $this->post('/register', $this->registrationPayload(['email' => 'mailed@example.test']));

        Mail::assertSent(\App\Mail\WelcomeMail::class, function ($mail) {
            return $mail->hasTo('mailed@example.test');
        });
    }

    /**
     * The issuer branch writes the uploaded file objects straight into
     * users.issuer_pros_doc / issuer_kyc_doc. The investor branch stores them
     * first and saves the resulting URL. An UploadedFile stringifies to its
     * temp path, which is gone by the time an admin opens the record.
     *
     * @test
     */
    public function issuer_registration_documents_are_stored_and_retrievable()
    {
        Mail::fake();

        $this->post('/issuer/register', $this->registrationPayload([
            'email'     => 'docs.issuer@example.test',
            'user_type' => User::USER_TYPE_ISSUER,
        ]));

        $user = User::where('email', 'docs.issuer@example.test')->firstOrFail();

        $this->assertNotEmpty($user->issuer_kyc_doc, 'No KYC document was recorded for the issuer.');
        $this->assertNotContains(
            'php',
            strtolower(basename($user->issuer_kyc_doc)),
            'The issuer KYC document points at a PHP upload temp file rather than stored content: ' . $user->issuer_kyc_doc
        );
    }

    /** @test */
    public function an_investors_registration_documents_are_stored()
    {
        Mail::fake();

        $this->post('/register', $this->registrationPayload(['email' => 'docs.investor@example.test']));

        $user = User::where('email', 'docs.investor@example.test')->firstOrFail();

        $this->assertNotEmpty($user->issuer_kyc_doc);
        $this->assertContains('storage/', $user->issuer_kyc_doc);
    }

    /** @test */
    public function a_registered_account_can_verify_its_email_token()
    {
        $user = $this->makeInvestor(['verified' => 0, 'email_token' => base64_encode('verify@example.test')]);

        $response = $this->get('/verifyemail/' . $user->email_token);

        $response->assertRedirect('/login');
        $this->assertSame(1, (int) $user->fresh()->verified);
    }

    // ----------------------------------------------------------- admin / kyc

    /** @test */
    public function an_admin_can_approve_a_users_account()
    {
        $admin    = $this->makeAdmin();
        $investor = $this->makeInvestor(['approved' => 0]);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.user.userApprovalStatus', [$investor->id, 'approve']));

        $response->assertStatus(302);
        $this->assertSame(1, (int) $investor->fresh()->approved);
    }

    /** @test */
    public function an_admin_can_block_and_unblock_a_user()
    {
        $admin    = $this->makeAdmin();
        $investor = $this->makeInvestor(['approved' => 1]);

        $this->actingAs($admin, 'admin')->get(route('admin.user.userApprovalStatus', [$investor->id, 'Block']));
        $this->assertSame(2, (int) $investor->fresh()->approved);

        $this->actingAs($admin, 'admin')->get(route('admin.user.userApprovalStatus', [$investor->id, 'Un-Block']));
        $this->assertSame(1, (int) $investor->fresh()->approved);
    }

    /** @test */
    public function an_admin_can_open_a_users_kyc_documents()
    {
        $admin    = $this->makeAdmin();
        $investor = $this->makeInvestor();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.user.kycdoc', $investor->id));

        $response->assertStatus(200);
    }

    /**
     * The admin KYC document screen (admin/user/document.blade.php) posts its
     * Approve and Reject buttons at admin.userdocument.approve /
     * admin.userdocument.reject. Neither action exists on AdminController.
     *
     * @test
     */
    public function the_kyc_document_approve_and_reject_actions_exist()
    {
        $missing = [];

        foreach (['admin.userdocument.approve', 'admin.userdocument.reject'] as $name) {
            $route  = Route::getRoutes()->getByName($name);
            $action = $route->getAction()['controller'];
            list($class, $method) = explode('@', $action);

            if (!method_exists($class, $method)) {
                $missing[] = $action;
            }
        }

        $this->assertSame([], $missing, 'The admin KYC document buttons point at controller actions that do not exist.');
    }

    /** @test */
    public function an_admin_can_approve_a_kyc_document_and_it_verifies_the_user()
    {
        $admin    = $this->makeAdmin();
        $investor = $this->makeInvestor(['kyc' => 0]);

        $document = Document::create(['name' => 'Passport', 'image' => '', 'order' => 1, 'type' => 'KYC', 'mandatory' => '1']);
        $kyc      = KycDocument::create([
            'user_id'     => $investor->id,
            'document_id' => $document->id,
            'url'         => 'documents/passport.pdf',
            'unique_id'   => uniqid(),
            'status'      => 'PENDING',
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.userdocument.approve'), [
            'user_id' => $investor->id,
            'doc_id'  => $document->id,
            'status'  => 'APPROVED',
        ]);

        $response->assertStatus(302);
        $response->assertSessionMissing('flash_error');

        $this->assertSame('APPROVED', $kyc->fresh()->status);
        $this->assertSame(1, (int) $investor->fresh()->kyc, 'Approving every mandatory document did not verify the user.');
    }

    /** @test */
    public function rejecting_a_kyc_document_removes_the_users_verified_status()
    {
        $admin    = $this->makeAdmin();
        $investor = $this->makeInvestor(['kyc' => 1]);

        $document = Document::create(['name' => 'Passport', 'image' => '', 'order' => 1, 'type' => 'KYC', 'mandatory' => '1']);
        $kyc      = KycDocument::create([
            'user_id'     => $investor->id,
            'document_id' => $document->id,
            'url'         => 'documents/passport.pdf',
            'unique_id'   => uniqid(),
            'status'      => 'APPROVED',
        ]);

        $this->actingAs($admin, 'admin')->post(route('admin.userdocument.approve'), [
            'user_id' => $investor->id,
            'doc_id'  => $document->id,
            'status'  => 'REJECTED',
        ]);

        $this->assertSame('REJECTED', $kyc->fresh()->status);
        $this->assertSame(0, (int) $investor->fresh()->kyc, 'A rejected mandatory document left the user KYC-verified.');
    }

    /** @test */
    public function a_user_is_not_verified_until_every_mandatory_document_is_approved()
    {
        $admin    = $this->makeAdmin();
        $investor = $this->makeInvestor(['kyc' => 0]);

        $passport = Document::create(['name' => 'Passport', 'image' => '', 'order' => 1, 'type' => 'KYC', 'mandatory' => '1']);
        $address  = Document::create(['name' => 'Proof of address', 'image' => '', 'order' => 2, 'type' => 'KYC', 'mandatory' => '1']);

        foreach ([$passport, $address] as $document) {
            KycDocument::create([
                'user_id'     => $investor->id,
                'document_id' => $document->id,
                'url'         => 'documents/doc.pdf',
                'unique_id'   => uniqid(),
                'status'      => 'PENDING',
            ]);
        }

        $this->actingAs($admin, 'admin')->post(route('admin.userdocument.approve'), [
            'user_id' => $investor->id,
            'doc_id'  => $passport->id,
            'status'  => 'APPROVED',
        ]);

        $this->assertSame(0, (int) $investor->fresh()->kyc, 'One of two mandatory documents was enough to verify the user.');

        $this->actingAs($admin, 'admin')->post(route('admin.userdocument.approve'), [
            'user_id' => $investor->id,
            'doc_id'  => $address->id,
            'status'  => 'APPROVED',
        ]);

        $this->assertSame(1, (int) $investor->fresh()->kyc);
    }

    /** @test */
    public function approving_a_document_the_user_never_uploaded_is_handled_cleanly()
    {
        $admin    = $this->makeAdmin();
        $investor = $this->makeInvestor();
        $document = Document::create(['name' => 'Passport', 'image' => '', 'order' => 1, 'type' => 'KYC', 'mandatory' => '1']);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.userdocument.approve'), [
            'user_id' => $investor->id,
            'doc_id'  => $document->id,
            'status'  => 'APPROVED',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('flash_error');
    }

    /** @test */
    public function an_admin_can_approve_an_accredited_kyc_document()
    {
        $admin    = $this->makeAdmin();
        $investor = $this->makeInvestor();

        $document = AccreditedDocument::create(['name' => 'Passport', 'image' => '', 'order' => 1, 'type' => 'KYC']);
        $kyc      = AccreditedKycDocument::create([
            'user_id'                => $investor->id,
            'accredited_document_id' => $document->id,
            'url'                    => 'documents/passport.pdf',
            'unique_id'              => uniqid(),
            'status'                 => 'PENDING',
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.useraccrediteddocument.approve'), [
            'user_id' => $investor->id,
            'doc_id'  => $document->id,
            'status'  => 'APPROVED',
        ]);

        $response->assertStatus(302);
        $response->assertSessionMissing('flash_error');
        $this->assertSame('APPROVED', $kyc->fresh()->status);
    }

    /** @test */
    public function an_admin_can_reject_an_accredited_kyc_document()
    {
        $admin    = $this->makeAdmin();
        $investor = $this->makeInvestor();

        $document = AccreditedDocument::create(['name' => 'Passport', 'image' => '', 'order' => 1, 'type' => 'KYC']);
        $kyc      = AccreditedKycDocument::create([
            'user_id'                => $investor->id,
            'accredited_document_id' => $document->id,
            'url'                    => 'documents/passport.pdf',
            'unique_id'              => uniqid(),
            'status'                 => 'PENDING',
        ]);

        $this->actingAs($admin, 'admin')->post(route('admin.useraccrediteddocument.reject'), [
            'user_id' => $investor->id,
            'doc_id'  => $document->id,
            'status'  => 'REJECTED',
        ]);

        $this->assertSame('REJECTED', $kyc->fresh()->status);
    }

    // ---------------------------------------------------------------- alerts

    /** @test */
    public function an_issuer_is_alerted_when_an_investor_submits_a_payment()
    {
        $issuer   = $this->makeIssuer();
        $investor = $this->makeInvestor();
        $contract = $this->deployAsset($issuer, self::TYPE_PROPERTY, ['supply' => 1000]);

        $service = new TokenPaymentService();
        $service->handleUpsertRequest($investor, $contract, ['currentStep' => 1, 'tokens' => 10, 'payby' => 'ETH']);
        $userToken = \App\UserToken::where('user_id', $investor->id)->firstOrFail();
        $service->setCustody($contract, $userToken, ['custody' => 'internal']);

        $service->savePaymentDetails($userToken, [
            'payment_method'      => \App\Enums\PaymentMethod::BANK_TRANSFER,
            'selected_payment_id' => 1,
            'payment_reference'   => 'REF-ALERT-1',
            'payment_proof'       => null,
        ], $contract);

        $this->assertTrue(
            Notification::where('user_id', $issuer->id)->where('title', 'Pending Payments')->exists(),
            'The issuer was not alerted that a payment is waiting for approval.'
        );
    }

    /** @test */
    public function an_investor_is_alerted_when_an_automatic_payment_succeeds()
    {
        $issuer   = $this->makeIssuer();
        $investor = $this->makeInvestor();
        $contract = $this->deployAsset($issuer, self::TYPE_PROPERTY, ['supply' => 1000]);

        $service = new TokenPaymentService();
        $service->handleUpsertRequest($investor, $contract, ['currentStep' => 1, 'tokens' => 10, 'payby' => 'ETH']);
        $userToken = \App\UserToken::where('user_id', $investor->id)->firstOrFail();
        $service->setCustody($contract, $userToken, ['custody' => 'internal']);

        // No contract argument: the automatic (crypto) path.
        $service->savePaymentDetails($userToken->fresh(), [
            'payment_method'      => \App\Enums\PaymentMethod::CRYPTO_TRANSFER,
            'selected_payment_id' => 1,
            'payment_reference'   => '0xpaid',
            'payment_proof'       => null,
        ]);

        $this->assertTrue(
            Notification::where('user_id', $investor->id)->where('title', 'Automatic Payment Successful')->exists(),
            'The investor was not alerted that their automatic payment succeeded.'
        );
    }

    /**
     * Both dashboards read unread alerts and then delete every notification the
     * user has. Anything raised while the investor happens to be on another page
     * is destroyed unseen, and reloading the dashboard clears the list.
     *
     * @test
     */
    public function opening_the_issuer_dashboard_does_not_destroy_unseen_alerts()
    {
        $issuer = $this->makeIssuer();

        Notification::create([
            'user_id'           => $issuer->id,
            'title'             => 'Pending Payments',
            'description'       => 'A payment is waiting for approval.',
            'notification_type' => 'info',
        ]);

        $first = $this->actingAs($issuer)->get('/issuer/dashboard');
        $this->assertCount(1, $first->original->getData()['notifications'], 'The alert was not delivered on first view.');

        $second = $this->actingAs($issuer)->get('/issuer/dashboard');
        $this->assertCount(0, $second->original->getData()['notifications'], 'The alert was delivered twice.');

        $this->assertSame(
            1,
            Notification::where('user_id', $issuer->id)->count(),
            'The alert was deleted on first view, so it is gone the moment the dashboard is reloaded.'
        );
        $this->assertSame(1, (int) Notification::where('user_id', $issuer->id)->first()->is_viewed);
    }

    /** @test */
    public function an_alert_raised_while_the_investor_is_elsewhere_survives_until_they_see_it()
    {
        $investor = $this->makeInvestor();

        // Investor loads their dashboard before anything has happened.
        $this->actingAs($investor)->get('/dashboard');

        Notification::create([
            'user_id'           => $investor->id,
            'title'             => 'Buy Request Completed Successfully',
            'description'       => 'Your tokens have been transferred.',
            'notification_type' => 'success',
        ]);

        $this->assertSame(
            1,
            Notification::where('user_id', $investor->id)->where('is_viewed', 0)->count(),
            'An alert raised after a dashboard visit was destroyed before the investor could see it.'
        );
    }

    // ----------------------------------------------------------------- setup

    private function makeAdmin(): Admin
    {
        $admin = new Admin();
        $admin->name           = 'Platform Admin';
        $admin->email          = 'admin' . uniqid() . '@example.test';
        $admin->password       = bcrypt('secret1234');
        $admin->picture        = '';
        $admin->wallet_address = '';
        $admin->save();

        return $admin;
    }

    private function registrationPayload(array $overrides = []): array
    {
        return array_merge([
            'name'                  => 'Test Person',
            'email'                 => 'person' . uniqid() . '@example.test',
            'password'              => 'Passw0rd!',
            'password_confirmation' => 'Passw0rd!',
            'user_type'             => User::USER_TYPE_INVESTOR,
            'country_id'            => 1,
            'account_type'          => 'individual',
            'issuer_pros_doc'       => UploadedFile::fake()->create('prospectus.pdf', 100, 'application/pdf'),
            'issuer_kyc_doc'        => UploadedFile::fake()->create('kyc.pdf', 100, 'application/pdf'),
            'list1'                 => 'on',
            'list2'                 => 'on',
            'list3'                 => 'on',
            'list4'                 => 'on',
        ], $overrides);
    }
}
