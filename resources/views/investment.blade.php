
@extends('layout.app')

@section('content')
{{-- <link href="{{ asset('issuer/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"
id="bootstrap-stylesheet" /> --}}
    <style>
        .input-error {
            border: 1px solid red !important;
            background-color: #ffe6e6;
        }

        .error-message {
            color: red;
            font-size: 0.875rem;
            margin-top: 5px;
            display: none;
        }
        .container {
            width: 100% !important;
        }
        .col-sm-4 {
                width:100%;
            }
        @media screen and (min-width:992px) {
            .col-sm-4 {
                width:33.33%;
            }
        }

        .modal-dialog {
            max-width: 1000px;
            width:100% !important;
        }
        .modal-content {
            width:100% !important;
            margin-left: 0 !important;
        }
    </style>
    <!-- Breadcrumb -->
    <div class="page-content">

        <div class="content">
            <input type="hidden" name="user_id"  id = 'user_id' value="{{ Auth::user()->id }}
">
            <!-- Start container-fluid -->
            <div class="container-fluid wizard-border">
                 <!-- Property Tab Starts -->
                <div class="property-tab">
                    <section class="container">
                        <!-- Top Details -->
                        <div class="top-details-sec">
                            <div class="row">
                                <div class="col-xl-4 col-md-4 mb-4">
                                    <div class="card border-0 text-light card-shadow">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="homepage_single">

                                                        <span class="bg-info text-center wb-icon-box bg_shedo_light"> <i
                                                                class="flaticon-asset" aria-hidden="true"></i> </span>
                                                        <div class="homepage_fl_right">
                                                            <h4 class="mt-0">Assets</h4>
                                                            <h3>{{ @count($shares) }} </h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-4 mb-4">
                                    <div class="card border-0 text-light card-shadow">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="homepage_single">

                                                        <span class="bg-success text-center wb-icon-box bg_shedo_light_green"><i
                                                                class="flaticon-bank" aria-hidden="true"></i></span>
                                                        <div class="homepage_fl_right">
                                                            <h4 class="mt-0">Net Investmnt</h4>
                                                            <h3>{{ number_format(@$totalNetInvestment, 2, '.', '') }}$</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>

                    <div class="pro-content-tab-wrap">
                        <div class="section">
                            <div class="tab-content">

                                <div role="tabpanel" class="tab-pane active show" id="my_earnings">
                                    <!--Table -->
                                    <section class="container table-property">
                                        <table class="datatable-full table table-striped table-bordered custom-table-style" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Property</th>
                                                    <th>Token Price</th>
                                                    <th>No of Token (IW)</th>
                                                    <th>Tokens (EW)</th>
                                                    <th>Total Tokens</th>
                                                    <th>View Transaction</th>
                                                    <th>view Token Holdings</th>
                                                    <th>Certificate</th>
                                                </tr>
                                            </thead>

                                            @foreach ($shares as $token)
                                                @if(($token->internal_wallet + $token->external_wallet) > 0)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ url('/viewproperty', $token->userContract->id) }}">{{ @$token->userContract->property->propertyName }}</a>
                                                        </td>

                                                        <td>{{ @$token->userContract->tokenvalue }}$</td>

                                                        {{-- IW Tokens with Transfer --}}
                                                        <td style="position: relative; text-align: center; padding-right: 80px; color: green;">
                                                            {{ @$token->internal_wallet ?? '0' }}
                                                            <button
                                                                class="btn btn-sm btn-outline-warning"
                                                                style="position: absolute; top: 50%; right: 5px; transform: translateY(-50%);"
                                                                @if(empty($token->internal_wallet) || $token->internal_wallet == 0) disabled @else
                                                                onclick="openTransferModal(
                                                                    '{{ $token->userContract->property->propertyName ?? '' }}',
                                                                    '{{ $token->user_contract_id }}',
                                                                    {{ $token->internal_wallet }},
                                                                )"
                                                                 @endif
                                                            >
                                                                Transfer
                                                            </button>
                                                        </td>

                                                        {{-- EW Tokens with View --}}
                                                        <td style="position: relative; text-align: center; padding-right: 80px; color: red;">
                                                            {{ @$token->external_wallet ?? '0' }}
                                                            <button
                                                                class="btn btn-sm btn-outline-primary view-wallets-btn"
                                                                style="position: absolute; top: 50%; right: 5px; transform: translateY(-50%);"
                                                                @if(empty($token->external_wallet) || $token->external_wallet == 0) disabled @else
                                                                    data-user-id="{{ $token->user_id }}"
                                                                    data-token-id="{{ $token->user_contract_id  }}"
                                                                    data-token-name="{{ $token->token_name }}"
                                                                    data-project-name="{{ $token->project->name ?? '' }}"
                                                                    data-issuer-name="{{ $token->issuer->name ?? '' }}"
                                                                @endif
                                                            >
                                                                View
                                                            </button>
                                                        </td>

                                                        <td>{{ @$token->external_wallet + @$token->internal_wallet }}</td>

                                                        <td>
                                                            <a href="{{ url('/view_investment', $token->userContract->id) }}">View</a>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-primary" target="_blank" href="{{ $token->userContract->blockchain->link.'token/'.$token->userContract->contract_address.'#balances'}}">View</a>
                                                        </td>

                                                        <td>
                                                            <a class="btn btn-primary" target="_blank" href="{{ route('certificate.view', ['id' => $token->userContract->id]) }}">View</a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </table>
                                    </section>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('components.transfer-modal')
        @include('components.external-wallet-investment')

    </div>

@endsection
@section('scripts')
<script>

    document.addEventListener("DOMContentLoaded", function () {

        document.querySelectorAll(".view-wallets-btn").forEach(button => {
            button.addEventListener("click", function () {
                const userId = this.dataset.userId;
                const tokenId = this.dataset.tokenId;
                const tokenName = this.dataset.tokenName;
                const projectName = this.dataset.projectName;
                const issuerName = this.dataset.issuerName;

                // Set modal fields
                document.getElementById("viewModalUserId").value = userId;
                document.getElementById("viewModalTokenId").value = tokenId;
                document.getElementById("viewModalTokenName").textContent = tokenName;

                // Load wallets with pagination
                loadExternalWallets(userId, tokenId, 1); // start with page 1

                // Show modal
                $("#viewExternalWalletsModal").modal("show");
            });
        });

    });

    function loadExternalWallets(userId, tokenId, page = 1) {
        const walletContainer = document.getElementById("externalWalletList");
        const noWalletsMessage = document.getElementById("noExternalWalletsMessage");
        const pagination = document.getElementById("walletPagination");

        walletContainer.innerHTML = "";
        pagination.innerHTML = "";
        noWalletsMessage.style.display = "none";

        fetch(`/${userId}/ewBalance/${tokenId}?page=${page}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.wallets.length > 0) {
                    data.wallets.forEach(wallet => {
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td class="text-break">${wallet.wallet_address}</td>
                            <td>${wallet.balance}</td>
                        `;
                        walletContainer.appendChild(row);
                    });

                    // Render pagination
                    for (let i = 1; i <= data.last_page; i++) {
                        const li = document.createElement("li");
                        li.className = `page-item ${i === data.current_page ? 'active' : ''}`;
                        li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                        li.addEventListener("click", function (e) {
                            e.preventDefault();
                            loadExternalWallets(userId, tokenId, i);
                        });
                        pagination.appendChild(li);
                    }

                } else {
                    noWalletsMessage.style.display = "block";
                }
            })
            .catch(() => {
                noWalletsMessage.style.display = "block";
            });
    }

    let availableTokens = 0;

    function openTransferModal(propertyName, tokenId, tokenCount) {
        const user_id = document.getElementById('user_id').value;

        $('#modalPropertyName').text(propertyName);
        $('#modalTokenId').val(tokenId);
        $('#modalAmountInput').val('');
        $('#modalRecipientAddress').empty();
        $('#amountValidationMsg').hide();

        // Set available token info
        $('#modalAvailableTokens').text(`Available: ${tokenCount} tokens`);
        $('#modalAmountInput').attr({ min: 1, max: tokenCount, required: true });


        // Set global value for validation
        availableTokens = tokenCount;

        // Fetch whitelisted addresses from: /{user_id}/whitelistAddress/{tokenId}
        fetch(`/${user_id}/whitelistAddress/${tokenId}`)
        .then(async res => {

            if (!res.ok) {
                const errorText = await res.text();
                console.error('Error response text:', errorText);
                throw new Error(`HTTP ${res.status}`);
            }

            const data = await res.json();
            console.log('Parsed JSON data:', data);

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.wallet_address;
                    document.getElementById('modalRecipientAddress').appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.disabled = true;
                option.textContent = 'No whitelisted addresses';
                document.getElementById('modalRecipientAddress').appendChild(option);
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
        });


        // Show modal
        $('#transferModal').modal('show');
    }

    $('#manageWhitelistBtn').on('click', function (e) {
        e.preventDefault();
        $('#transfer-section').hide();
        $('#whitelist-section').show();
    });

    $('#backToTransfer').on('click', function () {
        $('#whitelist-section').hide();
        $('#transfer-section').show();
    });

    $('#confirmTransferBtn').on('click', function () {
        const $btn = $(this);
        const tokenId = $('#modalTokenId').val();
        const userId = $('#user_id').val();

        const recipientWalletId = $('#modalRecipientAddress').val();
        const $amountInput = $('#modalAmountInput');
        const amount = parseInt($amountInput.val().trim(), 10);
        const $validationMsg = $('#amountValidationMsg');

        // Reset previous errors
        $amountInput.removeClass('input-error');
        $validationMsg.hide().text('');

        console.log(tokenId, recipientWalletId, amount);

        // Disable button to prevent double-clicks
        $btn.prop('disabled', true);

        // === Validate Amount ===
        if (!amount || isNaN(amount)) {
            $validationMsg.text('Amount is required').show();
            $amountInput.addClass('input-error');
            $btn.prop('disabled', false);
            return;
        }

        if (amount > availableTokens) {
            $validationMsg.text('Entered amount exceeds available tokens').show();
            $amountInput.addClass('input-error');
            $btn.prop('disabled', false);
            return;
        }

        // === Perform Transfer API Call ===
        fetch(`/${userId}/transferToken/${tokenId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                recipient_wallet_id: recipientWalletId,
                amount: amount
            })
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                $('#transfer-section').hide();
                location.reload();
            } else {
                $validationMsg.text(result.message || 'Transfer failed').show();
                $amountInput.addClass('input-error');
            }
        })
        .catch(err => {
            console.error('Transfer error:', err);
            $validationMsg.text('Something went wrong during transfer.').show();
            $amountInput.addClass('input-error');
        })
        .finally(() => {
            $btn.prop('disabled', false);
        });
    });


    $('#addWhitelistBtn').on('click', function () {
        const address = $('#newWhitelistAddress').val().trim();
        const user_id = $('#user_id').val();
        const contract_id = $('#modalTokenId').val();
        const errorText = document.getElementById('whitelistErrorText');

        if (!address) {
            errorText.textContent = 'Please enter a wallet address';
            errorText.classList.add('text-danger');
            errorText.classList.remove('text-success');
            return;
        }

        // Optionally validate Ethereum address pattern
        const ethRegex = /^0x[a-fA-F0-9]{40}$/;
        if (!ethRegex.test(address)) {
            errorText.textContent = 'Invalid wallet address format';
            errorText.classList.add('text-danger');
            errorText.classList.remove('text-success');
            return;
        }

        addToWhitelist(address, user_id, contract_id);
         // Switch to transfer modal

    });


    function addToWhitelist(address, user_id, contract_id) {

        const errorText = document.getElementById('whitelistErrorText');
        console.log(address, user_id, contract_id);
        $.ajax({
            url: `/${user_id}/whitelistAddress/${contract_id}`,
            type: "POST",
            data: {
                address: address,
                _token: '{{ csrf_token() }}'
            },
            success: function(results) {
                console.log(results);
                if (results.status) {
                    errorText.textContent = results.message || "Wallet address added successfully.";
                    errorText.classList.remove('text-danger');
                    errorText.classList.add('text-success');

                    if (results.code === 201) {
                        // Re-render the modalRecipientAddress dropdown from results.data
                        const dropdown = document.getElementById('modalRecipientAddress');
                        dropdown.innerHTML = ''; // Clear all previous options

                        const wallets = results.data;
                        wallets.forEach(wallet => {
                            const option = document.createElement('option');
                            option.value = wallet.id;
                            option.textContent = wallet.wallet_address;

                            // Auto-select the newly added one (case-insensitive)
                            if (wallet.wallet_address.toLowerCase() === address.toLowerCase()) {
                                option.selected = true;
                            }

                            dropdown.appendChild(option);
                        });


                    }
                    // setTimeout(closeWalletModal, 1500);


                }
            },
            error: function() {
                errorText.textContent = "Something went wrong while whitelisting.";
                errorText.classList.remove('text-success');
                errorText.classList.add('text-danger');
            }
        });
    }



</script>
@endsection
