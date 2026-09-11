@extends('issuer.layout.base')
@section('content')
<style>
    .keystore-entry {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 20px 0;
    }

    .balance-modal .modal-body {
        padding: 2rem;
    }

    .balance-result {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-top: 1rem;
    }

    .balance-loading {
        text-align: center;
        padding: 2rem;
    }

    .balance-error {
        color: #dc3545;
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        padding: 1rem;
        border-radius: 0.375rem;
        margin-top: 1rem;
    }

    .balance-success {
        color: #155724;
        background: #d4edda;
        border: 1px solid #c3e6cb;
        padding: 1rem;
        border-radius: 0.375rem;
        margin-top: 1rem;
    }

    /* Ensure modal backdrop works */
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1040;
        width: 100vw;
        height: 100vh;
        background-color: #000;
    }

    .modal-backdrop.show {
        opacity: 0.5;
    }

    .modal {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1050;
        display: none;
        width: 100%;
        height: 100%;
        overflow: hidden;
        outline: 0;
    }

    .modal.show {
        display: block;
    }
</style>

<div class="content-page-inner">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="content">
                    <div class="container-fluid p-0">
                        <h5 class="section-title">Keystore file Management (Blockchain Private Keys Management)</h5>
                        <p class="section-desc">
                            Manage your keystore and private keys in the platform. Private key is the key used to deploy blockchain assets (like tokens) and sign transactions. While KeyStore files are encrypted private key files protected with password. 
                            Your private keys are saved as encrypted keystore files on the platform which is protected with your password which means the private key and assets deployed are under your custody. Only you are the owner of these private keys and you have to make sure that both private key and password are in your custody and saved. Losing private key may result in loss of your deployed crypto assets in the blockchain so make sure you save both private key and password while generating keystore files on the platform through this module.
                        </p>
                        <p>
                            You can add one or more private keys in the platform
                        </p>
                        <a href="{{ route('keystore.getForm') }}" class="btn btn-primary">Create Key</a>

                        <div class="row mb-4 mx-3">
                            <div class="col-12">
                                @forelse ($keystores as $keystore)
                                <div class="row align-items-start my-4 p-3 mb-3 border rounded"
                                data-id="{{ $keystore->id }}"
                                data-title="{{ $keystore->title }}">
                           
                                   <!-- Title -->
                                   <div class="col-12 col-md-2">
                                       <strong id="{{ $keystore->id }}" class="d-block text-break">
                                           {{ $keystore->title }}
                                       </strong>
                                   </div>
                           
                                   <!-- Public Address -->
                                   <div class="col-12 col-md-3 mt-2 mt-md-0">
                                       <span class="d-inline-block px-2 py-1 bg-light border rounded text-monospace w-70">
                                           {{ $keystore->public_address }}
                                       </span>
                                   </div>
                           
                                   <!-- Actions -->
                                   <div class="col-12 col-md-7 mt-3 mt-md-0 d-flex justify-content-md-end gap-2">
                                    <button type="button" 
                                            class="btn btn-sm btn-info mx-1 check-balance-btn" 
                                            data-keystore-id="{{ $keystore->id }}"
                                            data-keystore-title="{{ $keystore->title }}"
                                            data-public-address="{{ $keystore->public_address }}"
                                            data-url="{{ url('api/balance')}}"
                                            >
                                        Check Balance
                                    </button>
                                    
                                    <a href="{{ route('keystore.retrieveForm', ['id' => $keystore->id]) }}"
                                        class="btn btn-sm btn-secondary mx-1">Retrieve Private Key</a>
                                     
                                     <a href="{{ route('keystore.editForm', ['id' => $keystore->id]) }}"
                                        class="btn btn-sm btn-primary mx-1">Edit</a>
                                   </div>
                                </div>
                                @empty
                                    <p>No keystore files found.</p>
                                @endforelse

                                {{-- Pagination --}}
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $keystores->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Balance Check Modal -->
<div class="modal fade" id="balanceModal" tabindex="-1" role="dialog" aria-labelledby="balanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="balanceModalLabel">Check Balance</h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label"><strong>Keystore:</strong></label>
                    <div id="keystoreInfo" class="p-2 bg-light rounded">
                        <div><strong>Title:</strong> <span id="modalKeystoreTitle"></span></div>
                        <div><strong>Address:</strong> <span id="modalPublicAddress" class="text-monospace"></span></div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="blockchainSelect" class="form-label"><strong>Select Blockchain:</strong></label>
                    <select class="form-control" id="blockchainSelect">
                        <option value="">Choose a blockchain...</option>
                        @foreach($blockchains as $blockchain)
                            <option value="{{ strtolower($blockchain['blockchain_name']) }}" 
                                    data-abbreviation="{{ $blockchain['abbreviation'] }}"
                                    data-chain-id="{{ $blockchain['id'] }}"
                                    data-link="{{ $blockchain['link'] }}"
                                    
                                    >
                                {{ $blockchain['blockchain_name'] }} ({{ $blockchain['abbreviation'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-primary" id="fetchBalanceBtn" disabled>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Fetch Balance
                    </button>
                </div>

                <div id="balanceResult" class="d-none">
                    <!-- Balance results will be displayed here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.copy-btn');
    const blockchainSelect = document.getElementById('blockchainSelect');
    const fetchBalanceBtn = document.getElementById('fetchBalanceBtn');
    const balanceResult = document.getElementById('balanceResult');
    
    let currentKeystoreId = null;
    let currentPublicAddress = null;

    // Copy functionality
    buttons.forEach(btn => {
        btn.addEventListener('click', function () {
            const text = this.getAttribute('data-clipboard-text');
            navigator.clipboard.writeText(text).then(() => {
                this.innerHTML = '✅';
                setTimeout(() => this.innerHTML = '📋', 1000);
            });
        });
    });

    // Balance check modal functionality
    document.querySelectorAll('.check-balance-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            currentKeystoreId = this.getAttribute('data-keystore-id');
            currentPublicAddress = this.getAttribute('data-public-address');
            baseUrl = this.getAttribute('data-url');

            // Update modal content
            document.getElementById('modalKeystoreTitle').textContent = this.getAttribute('data-keystore-title');
            document.getElementById('modalPublicAddress').textContent = currentPublicAddress;
            
            // Reset form
            blockchainSelect.value = '';
            fetchBalanceBtn.disabled = true;
            balanceResult.classList.add('d-none');
            balanceResult.innerHTML = '';

            // Show modal - Try different methods for compatibility
            if (typeof $ !== 'undefined' && $.fn.modal) {
                // Bootstrap 4 with jQuery
                $('#balanceModal').modal('show');
            } else if (typeof bootstrap !== 'undefined') {
                // Bootstrap 5
                const modal = new bootstrap.Modal(document.getElementById('balanceModal'));
                modal.show();
            } else {
                // Manual modal show
                const modal = document.getElementById('balanceModal');
                modal.style.display = 'block';
                modal.classList.add('show');
                document.body.classList.add('modal-open');
                
                // Create backdrop
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop show';
                backdrop.id = 'modal-backdrop';
                document.body.appendChild(backdrop);
            }
        });
    });

    // Manual modal close functionality
    function closeModal() {
        const modal = document.getElementById('balanceModal');
        modal.style.display = 'none';
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
        
        // Remove backdrop
        const backdrop = document.getElementById('modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
    }

    // Close modal on close button click
    document.querySelectorAll('[data-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', function() {
            if (typeof $ !== 'undefined' && $.fn.modal) {
                $('#balanceModal').modal('hide');
            } else if (typeof bootstrap !== 'undefined') {
                const modal = bootstrap.Modal.getInstance(document.getElementById('balanceModal'));
                if (modal) modal.hide();
            } else {
                closeModal();
            }
        });
    });

    // Close modal on backdrop click
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('modal-backdrop')) {
            closeModal();
        }
    });

    // Enable/disable fetch button based on blockchain selection
    blockchainSelect.addEventListener('change', function() {
        fetchBalanceBtn.disabled = !this.value;
    });

    // Fetch balance functionality
    fetchBalanceBtn.addEventListener('click', function() {
        const selectedOption = blockchainSelect.options[blockchainSelect.selectedIndex];
        const selectedBlockchain = blockchainSelect.value;
        
        if (!selectedBlockchain || !currentPublicAddress) {
            return;
        }

        
        // Get additional blockchain data from option attributes
        const abbreviation = selectedOption.getAttribute('data-abbreviation');
        const chainId = selectedOption.getAttribute('data-chain-id');
        const explorerLink = selectedOption.getAttribute('data-link');

        // Show loading state
        const spinner = this.querySelector('.spinner-border');
        if (spinner) spinner.classList.remove('d-none');
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Fetching...';

        // Hide previous results
        balanceResult.classList.add('d-none');

        // Create CSRF token meta tag if it doesn't exist
        let csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            csrfToken = document.createElement('meta');
            csrfToken.name = 'csrf-token';
            csrfToken.content = '{{ csrf_token() }}';
            document.head.appendChild(csrfToken);
        }

        const fullUrl = `${baseUrl}/${encodeURIComponent(currentPublicAddress)}?chain=${chainId}`;

        // fetch(`${baseUrl}/${encodeURIComponent(currentPublicAddress)}?chain=${chainId}`)

        // Make API call
        fetch(fullUrl, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            },
            
        })
        .then(response => response.json())
        .then(data => {
            // Reset button state
            this.disabled = false;
            this.innerHTML = 'Fetch Balance';

            // Show results
            balanceResult.classList.remove('d-none');
            if (data.status == 'success') {
                balanceResult.innerHTML = `
                    <div class="balance-success">
                        <h6><strong>Balance Information</strong></h6>
                        <div><strong>Balance:</strong> ${data.balance}</div>
                        <div class="mt-2">
                            <small class="text-muted">Last updated: ${new Date().toLocaleString()}</small>
                        </div>
                    </div>
                `;
            } else {
                conso
                balanceResult.innerHTML = `
                    <div class="balance-error">
                        <h6><strong>Error</strong></h6>
                        <div>${data.error || 'Failed to fetch balance. Please try again.'}</div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Reset button state
            this.disabled = false;
            this.innerHTML = 'Fetch Balance';

            // Show error
            balanceResult.classList.remove('d-none');
            balanceResult.innerHTML = `
                <div class="balance-error">
                    <h6><strong>Network Error</strong></h6>
                    <div>Unable to connect to the balance service. Please check your internet connection and try again.</div>
                </div>
            `;
        });
    });
})
</script>
@endsection