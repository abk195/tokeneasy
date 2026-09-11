<div class="modal fade" id="viewExternalWalletsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content p-4">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span id="viewModalTokenName"></span>  External Wallets
                </h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
            </div>

            <div class="px-4 pb-2 text-muted" id="viewModalProjectInfo"></div>

            <div class="modal-body">
                <input type="hidden" id="viewModalUserId">
                <input type="hidden" id="viewModalTokenId">

                <!-- Wallet Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Wallet Address</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody id="externalWalletList"></tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav>
                    <ul class="pagination justify-content-center mb-0" id="walletPagination"></ul>
                </nav>

                <!-- No Wallets Message -->
                <div id="noExternalWalletsMessage" class="text-center text-muted mt-3" style="display: none;">
                    No external wallets found for this token.
                </div>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>
