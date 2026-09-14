<?php

namespace Tests\Feature\ClientScenarios;

use App\AccreditedKycDocument;
use App\Document;
use App\UserCompanyDetails;
use App\UserIdentity;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\Support\ScenarioTestCase;

/**
 * The investor profile: every saved detail is shown back, and every document —
 * both the two uploaded at registration and the KYC documents uploaded from the
 * profile — is listed with a working link and its review status.
 *
 * Each save test goes through the real form handler and then reloads the page,
 * because "the data is showing properly" means what comes back after a save, not
 * just what a seeded row renders as.
 */
class Scenario09InvestorProfileTest extends ScenarioTestCase
{
    /** @var \App\User */
    private $investor;

    /** @var array ids of the seeded lookup rows */
    private $ref = [];

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.is_demo' => false]);

        // Uploads use the default disk. Its root is storage/app/public — the same
        // directory /storage serves — which is what makes img() links resolve.
        Storage::fake('local');
        Storage::fake('public');

        $this->seedLookups();

        $this->investor = $this->makeInvestor([
            'name'         => 'Aisha Rahman',
            'email'        => 'aisha.rahman@example.test',
            'account_type' => 'individual',
        ]);
    }

    // --------------------------------------------------------------- viewing

    /** @test */
    public function a_new_investor_can_open_their_profile_before_filling_anything_in()
    {
        $response = $this->actingAs($this->investor)->get('/profile');

        $response->assertStatus(200);
        $this->assertSame('Aisha', $this->inputValue($response, 'first_name'));
        $this->assertSame('Rahman', $this->inputValue($response, 'last_name'));
        $this->assertSame('aisha.rahman@example.test', $this->inputValue($response, 'email'));
    }

    /** @test */
    public function saved_identity_details_are_shown_back_in_the_form()
    {
        $this->saveIdentity($this->ref['city_af']);

        $response = $this->actingAs($this->investor)->get('/profile');
        $response->assertStatus(200);

        $this->assertSame('1990-04-12', $this->inputValue($response, 'dob'));
        $this->assertSame('Afghan', $this->inputValue($response, 'citizenship'));
        $this->assertSame('1001', $this->inputValue($response, 'postal_code'));
        $this->assertSame('701234567', $this->inputValue($response, 'primary_phone'));
        $this->assertSame('+93', $this->selectedValue($response, 'primary_country_code'));
        $this->assertSame('709876543', $this->inputValue($response, 'secondary_phone'));
        $this->assertSame('+971', $this->selectedValue($response, 'secondary_country_code'));
        $this->assertSame('12 Palm Street', $this->inputValue($response, 'address_line_1'));
        $this->assertSame('Apartment 4', $this->inputValue($response, 'address_line_2'));
        $this->assertSame('AF', $this->selectedValue($response, 'country_code'));
        $this->assertSame('Kabul Province', $this->inputValue($response, 'province'));
        $this->assertSame('Afghanistan', $this->inputValue($response, 'residence'));
        $this->assertSame((string) $this->ref['city_af'], $this->selectedValue($response, 'city_id'));
    }

    /**
     * The city list is rendered with cities() and no argument, which defaults to
     * Afghanistan ('AF'). JavaScript only reloads it when the country changes, so
     * on page load an investor anywhere else never sees their saved city — and
     * re-saving the form without touching the country replaces it with an Afghan
     * city.
     *
     * @test
     */
    public function the_saved_city_is_shown_for_an_investor_outside_the_default_country()
    {
        $this->saveIdentity($this->ref['city_ae'], 'AE');

        $response = $this->actingAs($this->investor)->get('/profile');

        $this->assertSame('AE', $this->selectedValue($response, 'country_code'));
        $this->assertSame(
            (string) $this->ref['city_ae'],
            $this->selectedValue($response, 'city_id'),
            'The investor\'s saved city (Dubai) is not selected; the city list only offers Afghan cities.'
        );
        $this->assertNotContains(
            'Kabul',
            $this->optionLabels($response, 'city_id'),
            'The city list for a UAE investor is showing Afghan cities.'
        );
    }

    /** @test */
    public function saving_identity_details_from_the_form_shows_them_on_the_profile()
    {
        $save = $this->actingAs($this->investor)->post(route('profile.identity'), [
            'first_name'             => 'Aisha',
            'last_name'              => 'Khan',
            'email'                  => 'aisha.rahman@example.test',
            'dob'                    => '1988-11-02',
            'citizenship'            => 'Emirati',
            'residence'              => 'Dubai',
            'postal_code'            => '00000',
            'primary_country_code'   => '+971',
            'primary_phone'          => '501112222',
            'secondary_country_code' => '+93',
            'secondary_phone'        => '701113333',
            'address_line_1'         => 'Marina Walk 5',
            'address_line_2'         => 'Tower B',
            'country_code'           => 'AE',
            'city_id'                => $this->ref['city_ae'],
            'province'               => 'Dubai',
        ]);

        $save->assertSessionHasNoErrors();
        $save->assertSessionHas('flash_success');

        $this->assertSame('Aisha Khan', $this->investor->fresh()->name, 'The account name was not updated.');

        $response = $this->actingAs($this->investor)->get('/profile');

        $this->assertSame('Khan', $this->inputValue($response, 'last_name'));
        $this->assertSame('1988-11-02', $this->inputValue($response, 'dob'));
        $this->assertSame('Emirati', $this->inputValue($response, 'citizenship'));
        $this->assertSame('+971', $this->selectedValue($response, 'primary_country_code'));
        $this->assertSame('501112222', $this->inputValue($response, 'primary_phone'));
        $this->assertSame('Marina Walk 5', $this->inputValue($response, 'address_line_1'));
        $this->assertSame('AE', $this->selectedValue($response, 'country_code'));
    }

    /** @test */
    public function saving_twice_updates_the_existing_details_rather_than_duplicating_them()
    {
        foreach (['Palm Street', 'Marina Walk'] as $street) {
            $this->actingAs($this->investor)->post(route('profile.identity'), [
                'first_name'     => 'Aisha',
                'last_name'      => 'Rahman',
                'email'          => 'aisha.rahman@example.test',
                'address_line_1' => $street,
                'country_code'   => 'AF',
            ]);
        }

        $this->assertSame(1, UserIdentity::where('user_id', $this->investor->id)->count());
        $this->assertSame('Marina Walk', UserIdentity::where('user_id', $this->investor->id)->value('address_line_1'));
    }

    // --------------------------------------------------- registration documents

    /** @test */
    public function registration_documents_are_linked_from_the_profile()
    {
        $this->investor->forceFill([
            'issuer_kyc_doc'  => 'https://tokeneasy.io/storage/issuer_kyc_doc/address-proof.pdf',
            'issuer_pros_doc' => 'https://tokeneasy.io/storage/issuer_doc/passport.pdf',
        ])->save();

        $response = $this->actingAs($this->investor)->get('/profile');

        $this->assertSame(
            'https://tokeneasy.io/storage/issuer_kyc_doc/address-proof.pdf',
            $this->documentLinkUnder($response, 'Proof of address'),
            'The address proof uploaded at registration is not linked.'
        );
        $this->assertSame(
            'https://tokeneasy.io/storage/issuer_doc/passport.pdf',
            $this->documentLinkUnder($response, 'Identification Document'),
            'The identification document uploaded at registration is not linked.'
        );
    }

    /** @test */
    public function missing_registration_documents_are_reported_as_not_uploaded()
    {
        $response = $this->actingAs($this->investor)->get('/profile');

        $this->assertNull($this->documentLinkUnder($response, 'Proof of address'));
        $this->assertNull($this->documentLinkUnder($response, 'Identification Document'));
        $this->assertSame(2, substr_count($response->getContent(), 'No document uploaded'));
    }

    /** @test */
    public function a_document_uploaded_at_registration_appears_on_the_profile()
    {
        $this->post('/register', [
            'name'                  => 'Omar Siddiqui',
            'email'                 => 'omar@example.test',
            'password'              => 'Passw0rd!',
            'password_confirmation' => 'Passw0rd!',
            'user_type'             => \App\User::USER_TYPE_INVESTOR,
            'country_id'            => 1,
            'account_type'          => 'individual',
            'issuer_pros_doc'       => UploadedFile::fake()->create('passport.pdf', 100, 'application/pdf'),
            'issuer_kyc_doc'        => UploadedFile::fake()->create('address.pdf', 100, 'application/pdf'),
            'list1' => 'on', 'list2' => 'on', 'list3' => 'on', 'list4' => 'on',
        ]);

        $user = \App\User::where('email', 'omar@example.test')->firstOrFail();
        $user->forceFill(['approved' => 1])->save();

        $response = $this->actingAs($user)->get('/profile');

        $address  = $this->documentLinkUnder($response, 'Proof of address');
        $identity = $this->documentLinkUnder($response, 'Identification Document');

        $this->assertNotNull($address, 'The address proof uploaded at registration is missing from the profile.');
        $this->assertNotNull($identity, 'The identification document uploaded at registration is missing from the profile.');

        // Registration stores on the public disk and saves an asset('storage/...')
        // URL, so each link should resolve to a stored file.
        foreach ([$address, $identity] as $url) {
            Storage::disk('public')->assertExists(\Illuminate\Support\Str::after($url, asset('storage') . '/'));
        }
    }

    // ---------------------------------------------------------- KYC documents

    /** @test */
    public function uploaded_kyc_documents_are_listed_with_their_name_both_sides_and_status()
    {
        $passport = $this->makeDocument('Passport', true);
        $utility  = $this->makeDocument('Utility Bill', false);

        $this->makeKycDocument($passport, 'APPROVED');
        $this->makeKycDocument($utility, 'REJECTED');

        $response = $this->actingAs($this->investor)->get('/profile');
        $html     = $response->getContent();

        foreach (['Passport', 'Utility Bill'] as $name) {
            $this->assertContains("{$name} Front Side", $html, "{$name} front side is not listed.");
            $this->assertContains("{$name} Back Side", $html, "{$name} back side is not listed.");
        }

        $this->assertContains('APPROVED', $html);
        $this->assertContains('REJECTED', $html);

        $this->assertContains(asset('storage/accredited_kyc/documents/passport-front.png'), $html);
        $this->assertContains(asset('storage/accredited_kyc/documents/passport-back.png'), $html);
    }

    /** @test */
    public function uploading_a_kyc_document_stores_both_sides_where_the_links_point()
    {
        $passport = $this->makeDocument('Passport', true);

        $upload = $this->actingAs($this->investor)->post(route('kyc-upload'), [
            'accredited_kyc_select' => $passport->id,
            'image'                 => UploadedFile::fake()->image('front.png'),
            'back_image'            => UploadedFile::fake()->image('back.png'),
        ]);

        $upload->assertSessionHasNoErrors();

        $doc = AccreditedKycDocument::where('user_id', $this->investor->id)->firstOrFail();

        $this->assertSame('PENDING', $doc->status);
        $this->assertSame($passport->id, (int) $doc->accredited_document_id);

        // Files land on the default disk; /storage serves the public disk. They
        // are the same directory, which is the only reason img() links work.
        $this->assertSame(
            config('filesystems.disks.public.root'),
            config('filesystems.disks.' . config('filesystems.default') . '.root'),
            'KYC uploads are stored somewhere /storage does not serve, so every document link would 404.'
        );
        Storage::disk(config('filesystems.default'))->assertExists($doc->url);
        Storage::disk(config('filesystems.default'))->assertExists($doc->back_url);

        $html = $this->actingAs($this->investor)->get('/profile')->getContent();
        $this->assertContains(asset('storage/' . $doc->url), $html, 'The front side link does not point at the stored file.');
        $this->assertContains(asset('storage/' . $doc->back_url), $html, 'The back side link does not point at the stored file.');
    }

    /** @test */
    public function uploading_again_for_the_same_document_replaces_the_earlier_upload()
    {
        $passport = $this->makeDocument('Passport', true);

        foreach (['first.png', 'second.png'] as $name) {
            $this->actingAs($this->investor)->post(route('kyc-upload'), [
                'accredited_kyc_select' => $passport->id,
                'image'                 => UploadedFile::fake()->image($name),
                'back_image'            => UploadedFile::fake()->image('back-' . $name),
            ]);
        }

        $this->assertSame(
            1,
            AccreditedKycDocument::where('user_id', $this->investor->id)->where('accredited_document_id', $passport->id)->count(),
            'Uploading the same document twice left two copies.'
        );
    }

    /** @test */
    public function a_kyc_upload_must_include_both_sides_as_images()
    {
        $passport = $this->makeDocument('Passport', true);

        $response = $this->actingAs($this->investor)->post(route('kyc-upload'), [
            'accredited_kyc_select' => $passport->id,
            'image'                 => UploadedFile::fake()->create('front.pdf', 50, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors(['image', 'back_image']);
        $this->assertSame(0, AccreditedKycDocument::where('user_id', $this->investor->id)->count());
    }

    /**
     * Each uploaded document that is not yet approved has an Edit button, which
     * posts both sides to update-kyc. updateKYC() reads $request->image into
     * $image but then stores $back_image, which is never assigned, so every edit
     * fails with a server error.
     *
     * @test
     */
    public function editing_a_kyc_document_replaces_both_sides_and_sends_it_back_for_review()
    {
        $passport = $this->makeDocument('Passport', true);
        $doc      = $this->makeKycDocument($passport, 'REJECTED');

        $response = $this->actingAs($this->investor)->post(route('update-kyc', $doc->id), [
            'image'      => UploadedFile::fake()->image('new-front.png'),
            'back_image' => UploadedFile::fake()->image('new-back.png'),
        ]);

        $this->assertNotSame(500, $response->getStatusCode(), 'Editing a KYC document crashed with a server error.');
        $response->assertSessionHas('flash_success');

        $doc->refresh();
        $this->assertSame('PENDING', $doc->status, 'An edited document was not sent back for review.');
        $this->assertNotSame('accredited_kyc/documents/passport-front.png', $doc->url, 'The front side was not replaced.');
        $this->assertNotSame('accredited_kyc/documents/passport-back.png', $doc->back_url, 'The back side was not replaced.');
        Storage::disk(config('filesystems.default'))->assertExists($doc->back_url);
    }

    /** @test */
    public function an_investor_cannot_edit_another_investors_kyc_document()
    {
        $passport = $this->makeDocument('Passport', true);
        $other    = $this->makeInvestor();
        $theirs   = AccreditedKycDocument::create([
            'user_id'                => $other->id,
            'accredited_document_id' => $passport->id,
            'url'                    => 'accredited_kyc/documents/theirs-front.png',
            'back_url'               => 'accredited_kyc/documents/theirs-back.png',
            'unique_id'              => uniqid(),
            'status'                 => 'APPROVED',
        ]);

        $this->actingAs($this->investor)->post(route('update-kyc', $theirs->id), [
            'image'      => UploadedFile::fake()->image('x.png'),
            'back_image' => UploadedFile::fake()->image('y.png'),
        ]);

        $theirs->refresh();
        $this->assertSame('APPROVED', $theirs->status);
        $this->assertSame('accredited_kyc/documents/theirs-front.png', $theirs->url);
    }

    /** @test */
    public function the_upload_form_is_offered_until_every_document_type_is_uploaded()
    {
        $passport = $this->makeDocument('Passport', true);
        $utility  = $this->makeDocument('Utility Bill', false);

        $before = $this->actingAs($this->investor)->get('/profile');
        $this->assertContains('KYC Verification', $before->getContent());
        $this->assertSame(['Passport', 'Utility Bill'], $this->documentChoices($before));

        $this->makeKycDocument($passport, 'PENDING');
        $this->makeKycDocument($utility, 'PENDING');

        $after = $this->actingAs($this->investor)->get('/profile');
        $this->assertNotContains('KYC Verification', $after->getContent(), 'The upload form is still offered after every document was uploaded.');
    }

    /** @test */
    public function approved_documents_cannot_be_edited_but_others_can()
    {
        $passport = $this->makeDocument('Passport', true);
        $utility  = $this->makeDocument('Utility Bill', false);

        $this->makeKycDocument($passport, 'APPROVED');
        $this->makeKycDocument($utility, 'REJECTED');

        // The button text breaks across lines between "Edit" and the name.
        $html = preg_replace('/\s+/', ' ', $this->actingAs($this->investor)->get('/profile')->getContent());

        $this->assertNotContains('Edit Passport', $html, 'An approved document can still be edited.');
        $this->assertContains('Edit Utility Bill', $html, 'A rejected document cannot be re-submitted.');
    }

    // ---------------------------------------------------------- company account

    /** @test */
    public function a_company_investor_sees_their_company_details_and_documents()
    {
        $this->investor->forceFill(['account_type' => 'company'])->save();

        // UserCompanyDetails guards user_id against mass assignment.
        (new UserCompanyDetails())->forceFill([
            'user_id'                   => $this->investor->id,
            'company_name'              => 'Rahman Holdings',
            'headquarters'              => 'Abu Dhabi',
            'date_founded'              => '2015-06-01',
            'team_size'                 => 42,
            'company_url'               => 'https://rahman.example',
            'social_channels'           => '@rahmanholdings',
            'incorporation_certificate' => 'company/incorporation.pdf',
        ])->save();

        $response = $this->actingAs($this->investor->fresh())->get('/profile');
        $response->assertStatus(200);

        $this->assertSame('Rahman Holdings', $this->inputValue($response, 'company_name'));
        $this->assertSame('Abu Dhabi', $this->inputValue($response, 'headquarters'));
        $this->assertSame('2015-06-01', $this->inputValue($response, 'company_date'));
        $this->assertSame('42', $this->inputValue($response, 'team_size'));
        $this->assertSame('https://rahman.example', $this->inputValue($response, 'company_url'));
        $this->assertContains('@rahmanholdings', $response->getContent());
        $this->assertContains(asset('storage/company/incorporation.pdf'), $response->getContent());
    }

    // ------------------------------------------- record shared with the issuer

    /**
     * UserIdentity::country_code used to be an accessor that parsed the phone
     * number, replacing the stored country. The fix keeps the stored column when
     * it is set, which is the investor case.
     *
     * @test
     */
    public function the_saved_country_is_read_back_as_the_country_not_the_phone_number()
    {
        $identity = $this->saveIdentity($this->ref['city_ae'], 'AE');

        $this->assertSame('AE', $identity->fresh()->country_code);
        $this->assertSame('United Arab Emirates', optional($identity->fresh()->country)->countryname);
    }

    /**
     * The issuer profile shares UserIdentity but stores "<dial code>-<number>" in
     * primary_phone and no country. Its form reads both halves back through the
     * same accessors, so they must keep working for that shape.
     *
     * @test
     */
    public function the_issuer_profile_still_shows_its_dial_code_and_number()
    {
        $issuer = $this->makeIssuer();

        $this->actingAs($issuer)->post('/issuer/profile_update', [
            'fname'        => 'Omar',
            'lname'        => 'Farouk',
            'country_code' => '+971',
            'mobileno'     => '501234567',
        ]);

        $this->assertSame('+971-501234567', UserIdentity::where('user_id', $issuer->id)->value('primary_phone'));

        $response = $this->actingAs($issuer->fresh())->get('/issuer/profile');
        $response->assertStatus(200);

        // The dial code is shown in a read-only input filled from primary_phone
        // directly; the number comes through UserIdentity::phone.
        $this->assertSame('+971', $this->inputValue($response, 'country_code'), 'The issuer\'s dial code is no longer shown.');
        $this->assertSame('501234567', $this->inputValue($response, 'mobileno'), 'The issuer\'s phone number is no longer shown.');
    }

    /** @test */
    public function an_issuer_reviewing_an_investors_kyc_sees_their_country_not_their_phone_number()
    {
        $this->saveIdentity($this->ref['city_ae'], 'AE');

        $issuer   = $this->makeIssuer();
        $response = $this->actingAs($issuer)->get('/issuer/view_kyc/' . $this->investor->id);
        $response->assertStatus(200);

        $html = preg_replace('/\s+/', ' ', $response->getContent());

        $this->assertContains('Country Code:</label> <div>AE</div>', $html);
        $this->assertNotContains('Country Code:</label> <div>701234567</div>', $html, 'The investor\'s phone number is shown as their country.');
    }

    // ----------------------------------------------------------------- setup

    private function seedLookups()
    {
        DB::table('countries')->insert([
            ['code' => 'AF', 'countrycode' => 'AFG', 'countryname' => 'Afghanistan'],
            ['code' => 'AE', 'countrycode' => 'ARE', 'countryname' => 'United Arab Emirates'],
        ]);

        $this->ref['city_af'] = DB::table('cities')->insertGetId(['country_code' => 'AF', 'name' => 'Kabul', 'status' => 1]);
        $this->ref['city_ae'] = DB::table('cities')->insertGetId(['country_code' => 'AE', 'name' => 'Dubai', 'status' => 1]);

        DB::table('country_codes')->insert([
            ['name' => 'Afghanistan', 'dial_code' => '+93', 'code' => 'AF'],
            ['name' => 'United Arab Emirates', 'dial_code' => '+971', 'code' => 'AE'],
        ]);
    }

    private function saveIdentity($cityId, $countryCode = 'AF')
    {
        return UserIdentity::create([
            'user_id'                => $this->investor->id,
            'dob'                    => '1990-04-12',
            'citizenship'            => 'Afghan',
            'residence'              => 'Afghanistan',
            'postal_code'            => '1001',
            'primary_country_code'   => '+93',
            'primary_phone'          => '701234567',
            'secondary_country_code' => '+971',
            'secondary_phone'        => '709876543',
            'address_line_1'         => '12 Palm Street',
            'address_line_2'         => 'Apartment 4',
            'country_code'           => $countryCode,
            'city_id'                => $cityId,
            'province'               => 'Kabul Province',
        ]);
    }

    private function makeDocument(string $name, bool $mandatory): Document
    {
        return Document::create([
            'name'      => $name,
            'image'     => '',
            'order'     => 1,
            'type'      => 'KYC',
            'mandatory' => $mandatory ? '1' : '0',
        ]);
    }

    private function makeKycDocument(Document $document, string $status): AccreditedKycDocument
    {
        $slug = strtolower(str_replace(' ', '-', $document->name));

        return AccreditedKycDocument::create([
            'user_id'                => $this->investor->id,
            'accredited_document_id' => $document->id,
            'url'                    => "accredited_kyc/documents/{$slug}-front.png",
            'back_url'               => "accredited_kyc/documents/{$slug}-back.png",
            'unique_id'              => uniqid(),
            'status'                 => $status,
        ]);
    }

    // ----------------------------------------------------------- DOM helpers

    private function xpath($response): \DOMXPath
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8"?>' . $response->getContent());
        libxml_clear_errors();

        return new \DOMXPath($dom);
    }

    private function inputValue($response, string $name)
    {
        $node = $this->xpath($response)->query("//input[@name='{$name}']")->item(0);

        return $node ? trim($node->getAttribute('value')) : null;
    }

    private function selectedValue($response, string $name)
    {
        $node = $this->xpath($response)->query("//select[@name='{$name}']/option[@selected]")->item(0);

        return $node ? $node->getAttribute('value') : null;
    }

    private function optionLabels($response, string $name): array
    {
        $labels = [];
        foreach ($this->xpath($response)->query("//select[@name='{$name}']/option") as $option) {
            $labels[] = trim($option->textContent);
        }

        return $labels;
    }

    private function documentChoices($response): array
    {
        $choices = [];
        foreach ($this->xpath($response)->query("//select[@name='accredited_kyc_select']/option[@value!='']") as $option) {
            $choices[] = trim(preg_replace('/\s*\(\s*(Mandatory|Optional)\s*\)\s*$/', '', trim($option->textContent)));
        }

        return $choices;
    }

    /**
     * The "View Document" link inside the card titled $title, or null.
     */
    private function documentLinkUnder($response, string $title)
    {
        $xpath = $this->xpath($response);
        $card  = $xpath->query("//h6[normalize-space(.)='{$title}']/ancestor::div[contains(@class,'card-body')][1]")->item(0);

        if (!$card) {
            return null;
        }

        $link = $xpath->query(".//a[contains(normalize-space(.), 'View Document')]", $card)->item(0);

        return $link ? $link->getAttribute('href') : null;
    }
}
