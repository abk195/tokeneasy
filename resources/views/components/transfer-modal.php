<div class="modal fade" id="transferModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content p-4">
            <div class="modal-header border-0">
                <h5 class="modal-title">Transfer <span id="modalPropertyName"></span> Tokens</h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="modalTokenId">
                <input type="hidden" id="modalContractId">
                <input type="hidden" id="modalWalletId">

                <!-- Section 1: Transfer Tokens -->
                <div id="transfer-section">
                    <div class="form-group">
                        <label for="modalAmountInput">Amount to Transfer</label>
                        <input type="number" id="modalAmountInput" class="form-control" placeholder="Enter number of tokens">
                        <small id="modalAvailableTokens" class="form-text text-muted">Available: 0 tokens</small>
                        <div class="invalid-feedback" id="amountValidationMsg"></div>
                    </div>

                    <div class="form-group">
                        <label for="modalRecipientAddress">Recipient Address</label>
                        <select class="form-control" id="modalRecipientAddress"></select>
                        <a href="#" class="btn btn-sm btn-link mt-2" id="manageWhitelistBtn">Add Whitelisted Addresses</a>
                    </div>
                </div>

                <!-- Section 2: Whitelist Management -->
                <div id="whitelist-section" style="display: none;">
                <div class="form-group">
                    <label>Add New Address</label>
                    <input type="text" id="newWhitelistAddress" class="form-control" placeholder="0x...">
                    <button type="button" class="btn btn-dark btn-sm mt-2" id="addWhitelistBtn">Add</button>
                    <small id="whitelistErrorText" class="d-block mt-2"></small>
                </div>

                    <div id="whitelistContainer"></div>
                    <button class="btn btn-link mt-3" id="backToTransfer">← Back to Transfer</button>
                </div>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="confirmTransferBtn">Confirm Transfer</button>
            </div>
        </div>
    </div>
</div>
