<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->name('adminlogin');
    // Route::get('/issuer/login', 'AdminAuth\LoginController@showLoginIssuerForm')->name('adminIssuerlogin');
    Route::post('/login', 'AdminAuth\LoginController@login');
    Route::post('/logout', 'AdminAuth\LoginController@logout')->name('adminlogout')->middleware(['web']);

    //Route::get('/issuer/register', 'AdminAuth\RegisterController@showIssuerRegistrationForm')->name('Issuerregister');
    //Route::post('/issuer/register', 'AdminAuth\RegisterController@register');

    Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'AdminAuth\RegisterController@register');


    Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
    Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset')->name('password.email');
    Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
    Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');

});

Route::post('/trigger_mail', 'HomeController@TriggerMail');
Route::get('/reset/password/{token}', 'HomeController@ShowResetForm');
Route::post('/check_password', 'HomeController@CheckPassword');

Route::get('privacy', function () {
    return view('privacypolicy');
});
Route::get('terms', function () {
    return view('terms-use');
});
Route::get('about_us', function () {
    return view('about-us');
});
Route::get('contact_us', function () {
    return view('contact-us');
});
Route::post('/contact/submit', 'FrontController@submitContactForm')->name('contact.submit');
// Route::get('/issuer/login', 'Auth\LoginController@showLoginIssuerForm')->name('adminIssuerlogin');
// Route::post('/issuer/login', 'AdminAuth\LoginController@login');

Route::get('/issuer/register', 'Auth\RegisterController@showIssuerRegistrationForm')->name('Issuerregister');
Route::post('/issuer/register', 'Auth\RegisterController@register_issuer');

// Route::get('/', 'Auth\LoginController@welcome');
// Route::get('/', 'HomeController@propertyList');

Route::get('/', 'FrontController@index');

Route::get('/marketplace', 'FrontController@marketplace')->name('marketplace');

Route::get('/pricing', 'FrontController@pricing')->name('pricing');

Route::get('/platform-purchase', 'FrontController@platformPurchase')->name('platform.purchase');

Route::get('/property/{id}', 'FrontController@propertyDetail')->name('front.property.detail');

Route::get('/purchase/{id}', 'FrontController@purchase')->name('front.purchase');

Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify')->name('email_verify');

Route::get('forgot', function () {
    return view('forgot');
});

Route::get('/success', function () {
    return view('success');
});

// Route::get('/wyre', 'WyreController@index');
// Route::get('/wyretest', 'WyreController@wyretest');

// Route::get('fiatpaymentstatus', 'WyreController@fiatpaymentstatus')->name('fiatpaymentstatus');

Auth::routes();
Route::get('/investor/login', 'Auth\LoginController@showLoginFormInvestor')->name('investor.login');
Route::post('/change/password', 'HomeController@update_password')->name('change.password')->middleware(['auth']);

Route::get('/profile', 'HomeController@profile')->name('profile')->middleware(['auth', 'investor']);
Route::post('/profile-identity', 'HomeController@profile_identity')->name('profile.identity')->middleware(['auth']);
Route::post('/profile_update', 'HomeController@profile_update')->name('profile_update')->middleware(['auth']);

Route::post('/kyc-upload', 'HomeController@userKYCUpload')->name('kyc-upload')->middleware(['auth', 'investor']);
Route::post('/update-kyc/{id}', 'HomeController@updateKYC')->name('update-kyc')->middleware(['auth']);
Route::post('usercompanydetail', 'HomeController@usercompanydetail')->name('usercompanydetail')->middleware(['auth']);

Route::group(['middleware' => ['auth', 'investor', 'user.kyc']], function () {
    // ********************** Investor ***********************

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/dashboard', 'HomeController@index')->name('dashboard');
    //Route::get('/propertyList', 'HomeController@propertyList')->name('propertyList');
    Route::get('/intel', 'HomeController@intel')->name('intel');
    //Route::get('/propertyDetail/{id}', 'HomeController@propertyDetail')->name('propertyDetail');
    Route::get('/applyInvest/{id}', 'HomeController@applyInvest')->name('applyInvest');

    Route::post('investstore', 'HomeController@investstore')->name('investstore');

    Route::get('/viewproperty/{id}', 'HomeController@ViewProperty');

    Route::get('walletBalanceAPI', 'HomeController@wallet_balance_api')->name('walletBalanceAPI');


    Route::get('security', 'UserController@security')->name('security');

    Route::get('participants', function () {
        return view('participants');
    });
    Route::get('prospectus', 'HomeController@prospectus');
    Route::post('prospectus_download', 'HomeController@prospectus_download')->name('prospectus_download');
    Route::get('kyc', 'HomeController@kyc');
    Route::post('personalinfo', 'HomeController@personalinfo');
    Route::post('userdetail', 'HomeController@userdetail')->name('userdetail');
    Route::post('kycstore', 'HomeController@kycstore');
    Route::post('accrediteduserstore', 'HomeController@accrediteduserstore');
    Route::post('accreditedkycstore', 'HomeController@accreditedkycstore');
    Route::post('usercompanypersonaldetail', 'HomeController@usercompanypersonaldetail')->name('usercompanypersonaldetail');
    Route::get('wallet', 'HomeController@wallet')->name('wallet');
    Route::post('/deposit_fiat', 'HomeController@DepositFiat');
    Route::get('/get_bank_details/{id}', 'HomeController@GetBankDetails');
    Route::post('min_invest_valid','HomeController@check_min_invest');
    Route::post('check_min_invest','HomeController@check_min_invest_paystack');

    // Route::get('security', 'HomeController@security')->name('security');
    Route::post('/g2fotpcheckenablenew', 'Google2FAController@g2fotpcheckenablenew');
    Route::get('withdrawETH', 'HomeController@withdrawETHBalance')->name('withdrawETH');
    Route::get('buytokens', 'HomeController@buyTokens')->name('buytokens');
    Route::post('/sendETH', 'HomeController@sendETH')->name('sendETH');
    Route::get('/generate/withdrawOTP', 'HomeController@generateWithdrawOTP')->name('generateWithdrawOTP');
    Route::post('privateKeyPassword', 'HomeController@privateKeyPassword')->name('privateKeyPassword');
    Route::get('privateKeyShow/{id}', 'HomeController@privateKeyShow')->name('privateKeyShow');
    Route::post('requestaddress', 'HomeController@requestaddress')->name('requestaddress');
    Route::post('addtoken', 'HomeController@addtoken')->name('addtoken');
    Route::post('withdraw', 'HomeController@withdraw')->name('withdraw');
    Route::get('/coinprice', 'HomeController@coinPrice');


    Route::get('balance', 'HomeController@balance_of_coin')->name('balance');
    Route::post('getcryptobalance', 'HomeController@getcryptobalance')->name('getcryptobalance');
    Route::post('gettokeninvestvalue', 'HomeController@gettokeninvestvalue')->name('gettokeninvestvalue');

    // ---------------- Invest --------------------
    Route::get('invest_token/{id}', 'HomeController@invest_token')->name('invest_token');
    Route::post('investstore', 'HomeController@investstore')->name('investstore');
    //Route::get('fiatpaymentstatus', 'HomeController@fiatpaymentstatus')->name('fiatpaymentstatus');
    // ---------------- Invest -------------------  -

    Route::get('dividend', 'HomeController@dividend');
    Route::get('voting', 'HomeController@showvoting');
    Route::post('votingstore', 'HomeController@votingstore')->name('votingstore');
    Route::get('support', function () {
        return view('support');
    });
    Route::post('supportstore', 'HomeController@supportstore')->name('supportstore');

    // Trade API

    Route::get('/get_live_balance/{coin}', 'HomeController@getCoinValues');
    Route::post('/place_trade', 'HomeController@PlaceTrade');
    Route::get('/open_trades', 'HomeController@OpenTrades');
    Route::get('/cancel_trade/{id}', 'HomeController@CancelTrade');
    Route::get('/trade', 'HomeController@GetTradeList');
    Route::get('/finish_trade/{id}', 'HomeController@UpdateTrade');
    Route::get('/trade_history', 'HomeController@TradeHistory');
    Route::get('/external_withdraw', 'HomeController@ExternalWithdraw');
    // ********************** Investor ***********************

    //Google 2 factor
    Route::get('g2fenablesuccess/{id}', 'UserController@g2fenablesuccess')->name('g2fenablesuccess');
    Route::get('/2fa/enable', 'Google2FAController@enableTwoFactor');
    Route::get('/2fa/disable', 'Google2FAController@disableTwoFactor');
    // Web check otp
    Route::post('/g2fotpcheckenable', 'Google2FAController@g2fotpcheckenable');

    Route::get('/flowchart', function(){
        return view('flowchart');
    });

    Route::get('/cancel_deposit/{id}', 'HomeController@CancelDepositRequest');
    Route::post('/withdraw_share', 'HomeController@CreateWithdrawShares');
    Route::get('/check_whitelist/{id}/{address}', 'HomeController@CheckWhiteList');
    Route::get('/get_propert_details/{id}', 'HomeController@GetPropertyDetails');

    // Deposit Crypto
    Route::post('/deposit_crypto', 'HomeController@DepositCrypto');
    Route::get('/get_share_detail/{id}', 'HomeController@GetShareDetail');

    // Token Buy Process
    Route::post('/{user_id}/purchaseRequest/{id}', 'HomeController@upsertPurchaseRequest')->name('upsertPurchaseRequest');
    Route::post('/{user_id}/updateInvestmentStep/{id}', 'HomeController@updateInvestmentStep')->name('updateInvestmentStep');
    Route::post('/{user_id}/discardPurchaseRequest/{id}', 'HomeController@discardPurchaseRequest')->name('discardPurchaseRequest');
    Route::post('/{user_id}/whitelistAddress/{id}', 'InvestorController@whitelistAddress')->name('whiteListedAddress');
    Route::get('/{user_id}/whitelistAddress/{id}', 'InvestorController@getWhiteListedAddress')->name('getWhiteListedAddress');
    Route::post('/{user_id}/transferToken/{token_id}', 'InvestorController@tranferTokensToEW')->name('tranferTokensToEW');
    Route::get('/{user_id}/ewBalance/{token_id}', 'InvestorController@getExternalWalletBalance')->name('getExternalWalletBalance');



    Route::get('/buy_requests', 'HomeController@getInvestorBuyRequest')->name('GetInvestorBuyRequest');

    Route::get('/investment', 'HomeController@investment')->name('investment');
    Route::get('/transfer', 'CryptoController@transfer')->name('transfer');

    // Crypto Transfer Routes
    Route::post('/crypto/transfer/store', 'CryptoController@store')->name('crypto.transfer.store');
    Route::put('/crypto/transfer/update/{id}', 'CryptoController@updateStatus')->name('crypto.transfer.update');

    // Test Token Routes
    Route::post('/test-tokens/request', 'CryptoController@requestTestTokens')->name('test.tokens.request');
    Route::get('/test-tokens', function() {
        return view('test-tokens');
    })->name('test.tokens.page');

    // Investment Routes
    Route::post('/check-before-pay', 'InvestmentController@checkBeforePay')->name('investment.checkBeforePay');
    Route::get('/network-configs', 'HomeController@getNetworkConfigs')->name('network.configs');
    Route::get('/test-network-switching', function() {
        return view('test-network-switching');
    })->name('test.network.switching');
    Route::get('/debug-network-configs', function() {
        $blockchains = \App\BlockchainModel::all();
        return response()->json([
            'blockchains' => $blockchains->toArray(),
            'count' => $blockchains->count()
        ]);
    })->name('debug.network.configs');
});

Route::get('/2fa/validate', 'Auth\LoginController@getValidateToken');
Route::post('/2fa/validate', ['middleware' => 'throttle:5', 'uses' => 'Auth\LoginController@postValidateToken']);

// API check otp
//Route::post('/gfaenablereal', 'Google2FAController@postValidateToken');
Route::post('/gfavalidateotp', 'Google2FAController@gfavalidateotp');


/*Route Real Estate STO*/


Route::get('/blog', 'HomeController@blog')->name('blog');
Route::get('/blogDetail', 'HomeController@blogDetail')->name('blogDetail'); //Dynami
//Route::get('/wallet', 'HomeController@wallet')->name('wallet');

Route::get('/view_investment/{id}', 'HomeController@ViewInvestment');

Route::get('/investDetail', 'HomeController@investDetail')->name('investDetail');
Route::get('/setting', 'HomeController@setting')->name('setting');
Route::get('/activity', 'HomeController@activity')->name('activity');
Route::get('/sellToken', 'HomeController@sellToken')->name('sellToken');
Route::post('/profile-finance', 'HomeController@profile_finance')->name('profile.finance');
Route::post('/profile-background', 'HomeController@profile_background')->name('profile.background');
Route::get('/propertyList', 'HomeController@propertyList')->name('propertyList');
Route::post('/pay_by_tazapay','HomeController@pay_by_tazapay');

Route::get('/certificate/{tokenTransaction}', 'IssuerController@getShareCertificate')->name('certificate.view');

Route::get('/under_construction', function () {
    return view('under_construction');
}
);

Route::get('/company',function () {
    return view('ourcompany');
}
);

Route::get('/intel',function () {
    return view('intel');
}
);

Route::get('/propertyList/{type?}', 'HomeController@propertyList')->name('propertyList');
Route::get('/property-asset-list/{type}', 'HomeController@propertyAssetList')->name('propertyAssetList');


Route::get('/propertyDetail/{id}', 'HomeController@propertyDetail')->name('propertyDetail'); // Dynamic

Route::get('/countrycity/{country}', 'UserController@countrycity')->name('countrycity');

Route::group(['prefix' => 'issuer', 'middleware' => ['auth', 'seller']], function () {
    Route::post('/g2fotpcheckenablenew', 'Google2FAController@g2fotpcheckenablenew');
    Route::get('/2fa/disable', 'Google2FAController@disableTwoFactor');
    Route::get('/setting', 'HomeController@setting')->name('setting');

    Route::get('/profile', 'IssuerController@profile')->name('profile');

    Route::get('/whitelist_request', 'IssuerController@WhitelistRequest');
    Route::get('/update_whitelist_request/{id}/{hash}/{status}', 'IssuerController@UpdateWhitelistRequest');
    Route::get('/whitelist_users', 'IssuerController@WhitelistUsers');

    Route::get('purchase_request', 'IssuerController@PurchaseRequest');
    Route::get('propertyBuyRequest', 'IssuerController@getPurchaseRequest');

    Route::get('/update_purchase_request/{id}/{status}', 'IssuerController@UpdatePurchaseRequest');
    Route::get('/update_buy_request/{id}/{status}', 'IssuerController@UpdateBuyRequest')->name('updateBuyRequest');


    Route::post('/profile_update', 'IssuerController@profile_update')->name('profile_update');
    Route::post('/usercompanydetail', 'IssuerController@usercompanydetail')->name('usercompanydetail');
    Route::get('/kyc', 'IssuerController@kyc')->name('kyc');
    Route::post('/kyc', 'IssuerController@updateKyc')->name('updateKyc');
    Route::get('/buy_requests', 'HomeController@getInvestorBuyRequest')->name('GetInvestorBuyRequest');



    Route::group(['middleware' => ['auth', 'seller', 'user.kyc']], function(){
        Route::get('/dashboard', 'IssuerController@dashboard')->name('dashboard');
        Route::get('/property', 'IssuerController@property')->name('property');
        Route::post('/property', 'IssuerController@storeProperty')->name('propertyStore');
        Route::get('/withdrawETH', 'IssuerController@withdrawETHBalance')->name('withdrawETH');
        Route::post('/sendETH', 'IssuerController@sendETH')->name('sendETH');
        Route::get('/generate/withdrawOTP', 'IssuerController@generateWithdrawOTP')->name('generateWithdrawOTP');
        Route::get('/token', 'IssuerController@token')->name('token');
        Route::get('/token-demo', 'IssuerDemoController@token')->name('token-demo');
        Route::get('/token/{id}', 'IssuerController@tokenEdit')->name('token.edit');
        Route::post('/get_country_ph_code','IssuerController@get_country_ph_code');
        Route::post('/token-update/{id}', 'IssuerController@tokenUpdate')->name('token.update');
        Route::get('/asset_fund', 'IssuerController@asset_fund')->name('asset_fund');
        Route::get('/utility-token', 'IssuerController@utilityToken')->name('utility-token');

        Route::get('/propertydetails/{id}', 'HomeController@propertyDetail')->name('issuer_propertyDetail');
        Route::get('/tokenList', 'IssuerController@tokenList')->name('tokenList');
        Route::get('/token-users', 'IssuerController@tokenUsers')->name('tokenUsers');

        Route::get('/token_history/{id}', 'IssuerController@TokenHistory');

        Route::get('/tokenRequest', 'IssuerController@tokenRequest')->name('tokenRequest');
        Route::get('/wallet', 'IssuerController@wallet')->name('issuerWallet');
        Route::get('/security', 'IssuerController@security')->name('issuersecurity');

        Route::post('/deposit_fiat', 'IssuerController@DepositFiat');

        Route::get('/get_live_balance/{coin}', 'IssuerController@getCoinValues');
        Route::get('/get_bank_details/{id}', 'IssuerController@GetBankDetails');

        Route::get('/investments', 'IssuerController@GetInvestTokens');
        Route::get('/trade', 'IssuerController@GetTradeList');
        Route::get('/open_trade', 'IssuerController@OpenTrade');
        Route::post('/place_trade', 'IssuerController@PlaceTrade');
        Route::get('/finish_trade/{id}', 'IssuerController@UpdateTrade');
        Route::get('/trade_history', 'IssuerController@TradeHistory');

        Route::get('/cancel_deposit/{id}', 'IssuerController@CancelDepositRequest');
        Route::get('/withdraw_share/{id}', 'IssuerController@ApproveWithdrawShare');

        // Deposit Crypto
        Route::post('/deposit_crypto', 'IssuerController@DepositCrypto');

        Route::get('/view_kyc/{id}', 'IssuerController@ViewInvestorKYC');

        // Keystore Routes
        Route::get('/keystore','KeystoreController@index')->name('keystore');
        Route::post('/issuer/keystore','KeystoreController@create')->name('keystore.create');
        Route::post('/issuer/generatePrivateKey','KeystoreController@generate')->name('keystore.generate');
        Route::post('/issuer/keystore/retrieve/{id}', 'KeystoreController@retrieve')->name('keystore.retrieve');
        Route::put('/issuer/keystore/{id}','KeystoreController@edit')->name('keystore.update');
        Route::get('/keystore/create','KeystoreController@getForm')->name('keystore.getForm');
        Route::get('/keystore/editForm/{id}','KeystoreController@editForm')->name('keystore.editForm');
        Route::get('/keystore/retrievFrom/{id}','KeystoreController@retrieveForm')->name('keystore.retrieveForm');

        // Payment routes
        Route::get('/payments/settings','IssuerPaymentController@index')->name('payments');
        Route::get('/payments/settings/addBank','IssuerPaymentController@getForm')->name('Bank.getForm');
        Route::post('/payments/settings/addBank','IssuerPaymentController@addBankAccount')->name('issuer.addBankAccount');
        Route::get('/payments/settings/editBank/{id}','IssuerPaymentController@getEditForm')->name('issuer.editForm');
        Route::put('/payments/settings/editBank/{id}','IssuerPaymentController@updateBankAccount')->name('issuer.updateBankAccount');
        Route::get('/payments/settings/update/{id}','IssuerPaymentController@getEditForm')->name('issuer.editForm');
        Route::get('/payments/settings/view/{id}','IssuerPaymentController@getView')->name('issuer.viewForm');


        Route::get('/payments/settings/crypto','IssuerPaymentController@cryptoIndex')->name('crypto.payments');
        Route::post('/payments/settings/crypto','IssuerPaymentController@saveCryptoAddresses')->name('crypto.payments.upsert');

    });


    Route::group(['prefix' => 'report'], function () {
        Route::get('/capital', [ReportController::class, 'capital'])->name('report.capital');
        Route::get('/investors', [ReportController::class, 'investors'])->name('report.investors');
        Route::get('/sales', [ReportController::class, 'sales'])->name('report.sales');
    });
});

// Test route for clearing project files
Route::get('/clear-project', [App\Http\Controllers\TestController::class, 'clear'])->name('clear.project');

// require, not require_once: route files must be read every time the router is
// built. require_once registers these only for the first application booted in a
// process, so any later boot (tests, queue workers, long-running servers) loses
// the Plaid routes and every view that links to plaid.index fails to render.
require __DIR__ . '/subroutes/plaid.php';