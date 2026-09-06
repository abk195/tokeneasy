<?php

namespace Tests\Feature\ClientScenarios;

use App\IssuerBankAccounts;
use App\IssuerStablecoinWalletAddress;
use App\KeystoreModel;
use Illuminate\Support\Facades\Crypt;
use Tests\Support\FakeNodeServer;
use Tests\Support\ScenarioTestCase;

/**
 * SCENARIO 2 — "Setup Keystores to deploy tokens and connect both banks and
 * crypto wallets with a project or asset from issuer dashboard."
 */
class Scenario02KeystoreAndPaymentSetupTest extends ScenarioTestCase
{
    /** @test */
    public function an_issuer_can_generate_a_keystore_from_the_dashboard()
    {
        $issuer = $this->makeIssuer();

        $response = $this->actingAs($issuer)->post(route('keystore.create'), [
            'method'            => 'generate',
            'title'             => 'Deployment key',
            'generate_password' => 'str0ngpassword',
        ]);

        $response->assertStatus(302);

        $keystore = KeystoreModel::where('user_id', $issuer->id)->first();

        $this->assertNotNull($keystore, 'No keystore was stored for the issuer.');
        $this->assertSame('Deployment key', $keystore->title);
        $this->assertSame('keystore-stub.json', $keystore->keystore_file_path);
        $this->assertNotEmpty($keystore->public_address);
        $this->assertSame(1, FakeNodeServer::callCount('/generateNewPrivateKey'));
        $this->assertSame(1, FakeNodeServer::callCount('/savePrivateKeytodisk'));
    }

    /** @test */
    public function an_issuer_can_import_an_existing_private_key_as_a_keystore()
    {
        $issuer = $this->makeIssuer();

        $this->actingAs($issuer)->post(route('keystore.create'), [
            'method'      => 'manual',
            'title'       => 'Imported key',
            'private_key' => '0xexisting-private-key',
            'password'    => 'str0ngpassword',
        ]);

        $keystore = KeystoreModel::where('user_id', $issuer->id)->first();

        $this->assertNotNull($keystore);
        $this->assertSame(0, FakeNodeServer::callCount('/generateNewPrivateKey'), 'An imported key should not be regenerated.');

        $saved = FakeNodeServer::lastRequest('/savePrivateKeytodisk');
        $this->assertSame('0xexisting-private-key', $saved['body']['privatekey'] ?? null);
        $this->assertSame('str0ngpassword', $saved['body']['password'] ?? null);
    }

    /** @test */
    public function the_keystore_password_is_stored_encrypted_not_in_clear_text()
    {
        $issuer = $this->makeIssuer();

        $this->actingAs($issuer)->post(route('keystore.create'), [
            'method'      => 'manual',
            'title'       => 'Imported key',
            'private_key' => '0xexisting-private-key',
            'password'    => 'str0ngpassword',
        ]);

        $stored = \DB::table('keystore')->where('user_id', $issuer->id)->value('encrypted_password');

        $this->assertNotSame('str0ngpassword', $stored, 'The keystore password is stored in clear text.');
        $this->assertSame('str0ngpassword', Crypt::decryptString($stored));
    }

    /** @test */
    public function retrieving_a_private_key_requires_the_correct_keystore_password()
    {
        $issuer   = $this->makeIssuer();
        $keystore = $this->makeKeystore($issuer);

        $wrong = $this->actingAs($issuer)->post(route('keystore.retrieve', $keystore->id), ['password' => 'wrong-password']);
        $wrong->assertSessionHas('error', 'Incorrect password.');
        $this->assertSame(0, FakeNodeServer::callCount('/readPrivateKeyFromDisk'));

        $right = $this->actingAs($issuer)->post(route('keystore.retrieve', $keystore->id), ['password' => 'keystore-password']);
        $right->assertSessionHas('private_key', '0xissuer-private-key');
    }

    /** @test */
    public function an_issuer_cannot_retrieve_another_issuers_keystore()
    {
        $owner     = $this->makeIssuer();
        $intruder  = $this->makeIssuer();
        $keystore  = $this->makeKeystore($owner);

        $response = $this->actingAs($intruder)->post(route('keystore.retrieve', $keystore->id), ['password' => 'keystore-password']);

        $response->assertSessionHas('error', 'Keystore not found.');
        $this->assertSame(0, FakeNodeServer::callCount('/readPrivateKeyFromDisk'));
    }

    /** @test */
    public function the_keystore_is_what_signs_the_deployment()
    {
        $issuer   = $this->makeIssuer();
        $keystore = $this->makeKeystore($issuer);
        $property = $this->makeProperty($issuer, self::TYPE_PROPERTY, $keystore);
        $request  = $this->makeTokenRequest($issuer, $property);

        (new \App\Services\TokenizerService())->deployToken($request->id);

        $read = FakeNodeServer::lastRequest('/readPrivateKeyFromDisk');
        $this->assertSame($keystore->keystore_file_path, $read['body']['filename']);
        $this->assertSame('keystore-password', $read['body']['password']);

        $balance = FakeNodeServer::lastRequest('/native_balance');
        $this->assertSame($keystore->public_address, $balance['body']['address']);
    }

    /** @test */
    public function an_issuer_can_attach_a_bank_account_to_a_deployed_asset()
    {
        $issuer   = $this->makeIssuer();
        $contract = $this->deployAsset($issuer, self::TYPE_PROPERTY);

        $response = $this->actingAs($issuer)->post(route('issuer.addBankAccount'), [
            'bank_name'         => 'Test National Bank',
            'bank_location'     => 'Dubai',
            'bank_address'      => '1 Bank Street',
            'bank_account_name' => 'Tokeneasy Issuer Ltd',
            'routing_details'   => 'TESTROUTING01',
            'beneficiary_name'  => 'Tokeneasy Issuer Ltd',
            'user_contract_id'  => $contract->id,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('flash_success');

        $bank = IssuerBankAccounts::where('issuer_id', $issuer->id)->first();

        $this->assertNotNull($bank, 'The bank account was not linked to the asset.');
        $this->assertSame($contract->id, (int) $bank->user_contract_id);
        $this->assertSame('Test National Bank', $bank->bank_name);
    }

    /** @test */
    public function a_bank_account_cannot_be_attached_to_an_asset_that_does_not_exist()
    {
        $issuer = $this->makeIssuer();

        $response = $this->actingAs($issuer)->post(route('issuer.addBankAccount'), [
            'bank_name'         => 'Test National Bank',
            'bank_location'     => 'Dubai',
            'bank_address'      => '1 Bank Street',
            'bank_account_name' => 'Tokeneasy Issuer Ltd',
            'beneficiary_name'  => 'Tokeneasy Issuer Ltd',
            'user_contract_id'  => 999999,
        ]);

        $response->assertSessionHasErrors('user_contract_id');
        $this->assertSame(0, IssuerBankAccounts::where('issuer_id', $issuer->id)->count());
    }

    /** @test */
    public function the_bank_settings_screen_only_lists_banks_for_the_selected_asset()
    {
        $issuer = $this->makeIssuer();
        $first  = $this->deployAsset($issuer, self::TYPE_PROPERTY);
        $second = $this->deployAsset($issuer, self::TYPE_RWA);

        foreach ([[$first, 'First Asset Bank'], [$second, 'Second Asset Bank']] as list($contract, $name)) {
            IssuerBankAccounts::create([
                'issuer_id'         => $issuer->id,
                'user_contract_id'  => $contract->id,
                'bank_name'         => $name,
                'bank_location'     => 'Dubai',
                'bank_address'      => '1 Bank Street',
                'bank_account_name' => 'Acct',
                'beneficiary_name'  => 'Beneficiary',
            ]);
        }

        $response = $this->actingAs($issuer)->get(route('payments', ['asset_id' => $second->id]));

        $response->assertStatus(200);
        $banks = $response->original->getData()['banks'];

        $this->assertCount(1, $banks);
        $this->assertSame('Second Asset Bank', $banks[0]->bank_name);
    }

    /** @test */
    public function an_issuer_can_attach_a_stablecoin_wallet_address_to_a_deployed_asset()
    {
        $issuer   = $this->makeIssuer();
        $contract = $this->deployAsset($issuer, self::TYPE_PROPERTY);

        $response = $this->actingAs($issuer)->post(route('crypto.payments.upsert'), [
            'contract_id'   => $contract->id,
            'blockchain_id' => $this->blockchain->id,
            'addresses'     => [$this->blockchainStablecoin->id => '0xIssuerReceivingWallet0000000000000001'],
        ]);

        $response->assertStatus(302);

        $wallet = IssuerStablecoinWalletAddress::where('issuer_id', $issuer->id)
            ->where('user_contract_id', $contract->id)
            ->where('blockchain_stablecoin_id', $this->blockchainStablecoin->id)
            ->first();

        $this->assertNotNull($wallet, 'The crypto wallet was not linked to the asset.');
        $this->assertSame('0xIssuerReceivingWallet0000000000000001', $wallet->address);
    }

    /** @test */
    public function clearing_a_stablecoin_address_removes_it_from_the_asset()
    {
        $issuer   = $this->makeIssuer();
        $contract = $this->deployAsset($issuer, self::TYPE_PROPERTY);

        $this->actingAs($issuer)->post(route('crypto.payments.upsert'), [
            'contract_id'   => $contract->id,
            'blockchain_id' => $this->blockchain->id,
            'addresses'     => [$this->blockchainStablecoin->id => '0xIssuerReceivingWallet0000000000000001'],
        ]);

        $this->actingAs($issuer)->post(route('crypto.payments.upsert'), [
            'contract_id'   => $contract->id,
            'blockchain_id' => $this->blockchain->id,
            'addresses'     => [$this->blockchainStablecoin->id => ''],
        ]);

        $this->assertSame(
            0,
            IssuerStablecoinWalletAddress::where('user_contract_id', $contract->id)
                ->where('blockchain_stablecoin_id', $this->blockchainStablecoin->id)
                ->count()
        );
    }

    /**
     * storeDefaultPayments() runs right after a deploy and wires the platform's
     * own stablecoin wallet to the new asset, so crypto buys work out of the box.
     *
     * @test
     */
    public function deploying_an_asset_wires_up_the_default_stablecoin_payment_route()
    {
        $issuer   = $this->makeIssuer();
        $property = $this->makeProperty($issuer, self::TYPE_PROPERTY);
        $request  = $this->makeTokenRequest($issuer, $property);

        $this->actingAs($issuer);
        (new \App\Services\TokenizerService())->deployToken($request->id);
        (new \App\Http\Controllers\IssuerController())->storeDefaultPayments($request->fresh());

        $contract = \App\UserContract::where('property_id', $property->id)->first();
        $wallet   = IssuerStablecoinWalletAddress::where('user_contract_id', $contract->id)->first();

        $this->assertNotNull($wallet, 'No default payment wallet was configured for the new asset.');
        $this->assertSame(config('token.token.address'), $wallet->address);
        $this->assertSame($this->blockchainStablecoin->id, (int) $wallet->blockchain_stablecoin_id);
    }
}
