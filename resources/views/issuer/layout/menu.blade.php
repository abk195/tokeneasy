<!-- Menu -->

<aside id="layout-menu" class="layout-menu menu-vertical menu">
<div class="app-brand demo">
    <a href="index.html" class="app-brand-link">
    <span class="app-brand-logo demo">
        <span class="text-primary">
            <img src="{{ $logo }}" alt="{{$project_name}}" width="80">
        {{-- <svg
            width="25"
            viewBox="0 0 25 42"
            version="1.1"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink">
            <defs>
            <path
                d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                id="path-1"></path>
            <path
                d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                id="path-3"></path>
            <path
                d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                id="path-4"></path>
            <path
                d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                id="path-5"></path>
            </defs>
            <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                <g id="Icon" transform="translate(27.000000, 15.000000)">
                <g id="Mask" transform="translate(0.000000, 8.000000)">
                    <mask id="mask-2" fill="white">
                    <use xlink:href="#path-1"></use>
                    </mask>
                    <use fill="currentColor" xlink:href="#path-1"></use>
                    <g id="Path-3" mask="url(#mask-2)">
                    <use fill="currentColor" xlink:href="#path-3"></use>
                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                    </g>
                    <g id="Path-4" mask="url(#mask-2)">
                    <use fill="currentColor" xlink:href="#path-4"></use>
                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                    </g>
                </g>
                <g
                    id="Triangle"
                    transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                    <use fill="currentColor" xlink:href="#path-5"></use>
                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                </g>
                </g>
            </g>
            </g>
        </svg> --}}
        </span>
    </span>
    <span class="app-brand-text demo menu-text fw-bold ms-2">{{$project_name}}</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
    <i class="icon-base bx bx-chevron-left"></i>
    </a>
</div>

<div class="menu-inner-shadow"></div>

@php
    // One place decides which menu entry the current page belongs to.
    //
    // Each parent is active when any of its children is, so a parent and its
    // toggle can no longer disagree. Sub-pages (edit, detail, create, view) are
    // listed with the entry they belong to, and patterns are exact wherever a
    // wildcard would swallow a sibling: `issuer/token*` used to match
    // tokenRequest, tokenList and the token edit page as well as Create
    // Property token, so two entries lit up at once.
    $demo = !empty($isDemo);
    $on = function (array $patterns) { return request()->is(...$patterns); };

    $menu = [
        'dashboard'       => $on(['issuer/dashboard']),
        'deposit'         => $on(['issuer/wallet']),
        'withdraw'        => $on(['issuer/withdrawETH']),
        'assetToken'      => $on(['issuer/asset_fund']),
        'propertyToken'   => $on(['issuer/token', 'issuer/token-demo']),
        'utilityToken'    => $on(['issuer/utility-token']),
        'pendingAssets'   => $on(['issuer/tokenRequest']),
        'assetsList'      => $on(['issuer/property', 'issuer/propertydetails/*', 'issuer/token/*',
                                  'issuer/tokenList', 'issuer/token-users', 'issuer/token_history/*']),
        'purchaseRequest' => $on(['issuer/purchase_request']),
        'keystore'        => $on(['issuer/keystore', 'issuer/keystore/*']),
        'banks'           => $on(['issuer/payments/settings', 'issuer/payments/settings/addBank',
                                  'issuer/payments/settings/editBank/*', 'issuer/payments/settings/update/*',
                                  'issuer/payments/settings/view/*']),
        'crypto'          => $on(['issuer/payments/settings/crypto']),
        'pendingPayments' => $on(['issuer/propertyBuyRequest']),
        'paymentHistory'  => $on(['issuer/buy_requests']),
        'plaid'           => $on(['issuer/plaid*']),
        'capital'         => $on(['issuer/report/capital']),
        'sales'           => $on(['issuer/report/sales']),
        'investors'       => $on(['issuer/report/investors']),
    ];

    $group = [
        'wallet'   => $menu['deposit'] || $menu['withdraw'],
        // Pending Assets is hidden from the menu, but its page is still reachable
        // by URL, so it keeps the Create Asset group open.
        'create'   => $menu['assetToken'] || $menu['propertyToken'] || $menu['utilityToken'] || $menu['pendingAssets'],
        'deployed' => $menu['assetsList'] || $menu['purchaseRequest'],
        'payments' => $menu['banks'] || $menu['crypto'] || $menu['pendingPayments'] || $menu['paymentHistory'] || $menu['plaid'],
        'reports'  => $menu['capital'] || $menu['sales'] || $menu['investors'],
    ];

    $state    = function ($isActive) { return $isActive ? 'active' : ''; };
    $groupFor = function ($isActive) { return $isActive ? 'active open' : ''; };
    $disabled = $demo ? 'disabled' : '';
    $href     = function ($url) use ($demo) { return $demo ? 'javascript:void(0);' : $url; };
@endphp

<ul class="menu-inner py-1">
    <li class="menu-item {{ $state($menu['dashboard']) }}">
        <a href="{{ url('issuer/dashboard') }}" class="menu-link">
            <i class="menu-icon icon-base bx bx-home-smile"></i>
            <div data-i18n="Dashboard">Dashboard</div>
        </a>
    </li>
    <li class="menu-item {{ $disabled }} {{ $groupFor($group['wallet']) }}">
        <a href="javascript:void(0);" class="menu-link {{ $demo ? '' : 'menu-toggle' }}">
            <i class="menu-icon icon-base bx bx-wallet"></i>
            <div data-i18n="Wallet">Wallet</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ $disabled }} {{ $state($menu['deposit']) }}">
                <a href="{{ $href(url('issuer/wallet')) }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-down-arrow-circle"></i>
                    <div data-i18n="Deposit">Deposit</div>
                </a>
            </li>
            <li class="menu-item {{ $disabled }} {{ $state($menu['withdraw']) }}">
                <a href="{{ $href(url('issuer/withdrawETH')) }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-up-arrow-circle"></i>
                    <div data-i18n="Withdraw">Withdraw</div>
                </a>
            </li>
        </ul>
    </li>
    {{-- In demo mode the group stays open so the Create Property token entry is visible. --}}
    <li class="menu-item {{ $groupFor($demo || $group['create']) }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle create-asset-highlight">
            <i class="menu-icon icon-base bx bx-plus-circle"></i>
            <div data-i18n="Create Asset">Create Asset</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ $disabled }} {{ $state($menu['assetToken']) }}">
                <a href="{{ $href(url('issuer/asset_fund')) }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-coin-stack"></i>
                    <div data-i18n="Create Asset token">Create Asset token</div>
                </a>
            </li>
            <li class="menu-item {{ $state($menu['propertyToken']) }}">
                <a href="{{ $demo ? url('issuer/token-demo') : url('issuer/token') }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-building-house"></i>
                    <div data-i18n="Create Property token">Create Property token</div>
                </a>
            </li>
            <li class="menu-item {{ $disabled }} {{ $state($menu['utilityToken']) }}">
                <a href="{{ $href(url('issuer/utility-token')) }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-cog"></i>
                    <div data-i18n="Create Utility token">Create Utility token</div>
                </a>
            </li>
            {{--
                Pending Assets is hidden: tokens deploy directly on creation and are
                no longer queued for admin review. The page is still reachable at
                /issuer/tokenRequest and lists only deployments that did not finish.
                Restore this entry if issuers need a way back to it from the menu.

            <li class="menu-item {{ $disabled }} {{ $state($menu['pendingAssets']) }}">
                <a href="{{ $href(url('issuer/tokenRequest')) }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-time"></i>
                    <div data-i18n="Pending Assets">Pending Assets</div>
                </a>
            </li>
            --}}
        </ul>
    </li>
    <li class="menu-item {{ $groupFor($group['deployed']) }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon icon-base bx bx-building"></i>
            <div data-i18n="Deployed Assets">Deployed Assets</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ $state($menu['assetsList']) }}">
                <a href="{{ url('issuer/property') }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-list-ul"></i>
                    <div data-i18n="Assets List">Assets List</div>
                </a>
            </li>
            <li class="menu-item {{ $state($menu['purchaseRequest']) }}">
                <a href="{{ url('/issuer/purchase_request') }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-shopping-bag"></i>
                    <div data-i18n="Property Purchase Request">Property Purchase Request</div>
                </a>
            </li>
        </ul>
    </li>
    <li class="menu-item {{ $disabled }} {{ $state($menu['keystore']) }}">
        <a href="{{ $href(url('issuer/keystore')) }}" class="menu-link">
            <i class="menu-icon icon-base bx bx-key"></i>
            <div data-i18n="Manage Keystore">Manage Keystore</div>
        </a>
    </li>
    <li class="menu-item {{ $disabled }} {{ $groupFor($group['payments']) }}">
        <a href="javascript:void(0);" class="menu-link {{ $demo ? '' : 'menu-toggle' }}">
            <i class="menu-icon icon-base bx bx-credit-card"></i>
            <div data-i18n="Payments">Payments</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ $disabled }} {{ $state($menu['banks']) }}">
                <a href="{{ $href(route('payments')) }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-bank"></i>
                    <div data-i18n="Add Banks">Add Banks</div>
                </a>
            </li>
            <li class="menu-item {{ $disabled }} {{ $state($menu['crypto']) }}">
                <a href="{{ $href(route('crypto.payments')) }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-bitcoin"></i>
                    <div data-i18n="Manage Crypto Address">Manage Crypto Address</div>
                </a>
            </li>
            <li class="menu-item {{ $disabled }} {{ $state($menu['pendingPayments']) }}">
                <a href="{{ $href(url('/issuer/propertyBuyRequest')) }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-time"></i>
                    <div data-i18n="Pending Payments">Pending Payments</div>
                </a>
            </li>
            <li class="menu-item {{ $disabled }} {{ $state($menu['paymentHistory']) }}">
                <a href="{{ $href(url('/issuer/buy_requests')) }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-history"></i>
                    <div data-i18n="Payment History">Payment History</div>
                </a>
            </li>
            @if($pageVisibility->get('plaid')['value'])
            <li class="menu-item {{ $disabled }} {{ $state($menu['plaid']) }}">
                <a href="{{ $href(route('plaid.index')) }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-link"></i>
                    <div data-i18n="Connect Your Bank">Connect Your Bank</div>
                </a>
            </li>
            @endif
        </ul>
    </li>
    <li class="menu-item {{ $disabled }} {{ $groupFor($group['reports']) }}">
        <a href="javascript:void(0);" class="menu-link {{ $demo ? '' : 'menu-toggle' }}">
            <i class="menu-icon icon-base bx bx-bar-chart-alt-2"></i>
            <div data-i18n="Reports">Reports</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ $disabled }} {{ $state($menu['capital']) }}">
                <a href="{{ $href(route('report.capital')) }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-dollar-circle"></i>
                    <div data-i18n="Capital">Capital</div>
                </a>
            </li>
            <li class="menu-item {{ $disabled }} {{ $state($menu['sales']) }}">
                <a href="{{ $href(route('report.sales')) }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-trending-up"></i>
                    <div data-i18n="Sales">Sales</div>
                </a>
            </li>
            <li class="menu-item {{ $disabled }} {{ $state($menu['investors']) }}">
                <a href="{{ $href(route('report.investors')) }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-group"></i>
                    <div data-i18n="Investors">Investors</div>
                </a>
            </li>
        </ul>
    </li>
</ul>
</aside>

<div class="menu-mobile-toggler d-xl-none rounded-1">
<a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
    <i class="bx bx-menu icon-base"></i>
    <i class="bx bx-chevron-right icon-base"></i>
</a>
</div>
<!-- / Menu -->
