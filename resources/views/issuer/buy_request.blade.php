@extends('issuer.layout.base')

@section('content')
<style>
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>
    <!-- Start Page Content here -->
    <div class="content-page-inner">

        <!-- Header Banner Start -->
        <div class="header-breadcrumbs">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1>Pending Payments</h1>
                    </div>
                    <div class="col-sm-6">
                        @include('issuer.layout.breadcrumb',['items' => [
                            [
                                'url' => 'issuer/dashboard',
                                'title' => 'Dashboard'
                            ],
                            [
                                'title' => 'Pending Payments'
                            ]
                        ]])
                    </div>
                </div>
            </div>
        </div>
        <!-- Header Banner Start -->

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="content">
                        <div class="container-fluid wizard-border">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <div>
                                        <table id="example1" class="table table-bordered">
                                            <thead>
                                                <tr>

                                                    <th>S.No</th>
                                                    <th>Investor Name</th>
                                                    <th>Asset Name</th>
                                                    <th>Quantity</th>
                                                    <th>Total Amount</th>
                                                    <th>Payment method</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($requests))
                                                    @foreach($requests as $index => $value)
                                                    <tr>
                                                        <td>{{$index+1}}</td>
                                                        <td>{{$value->user->name}}</td>
                                                        <td>{{$value->property ? $value->property->propertyName : 'N/A'}}</td>
                                                        <td>{{$value->token_acquire}}</td>
                                                        <td>{{ number_format((float)($value->commission ?? 0) + (float)($value->deal_amount ?? 0), 2) }}</td>
                                                        <td>{{ ucwords(str_replace('_', ' ', $value->payment_mode)) }}</td>
                                                        <td>
                                                            @php
                                                                $data = [
                                                                    "user" => $value->user,
                                                                    "propertyDetails" => $value->property,
                                                                    "request" => $value,
                                                                    "user_details" => $value->user->identity,
                                                                    "payment_proof_img_url" => img($value->payment_proof_url ?? null),
                                                                ];
                                                            @endphp

                                                            <button class="btn btn-success btn-sm"
                                                                    onclick='openApproveModal(@json($data))'>
                                                                Approve
                                                            </button>

                                                            <button class="btn btn-danger btn-sm"
                                                                    onclick="openRejectModal({{ $value->id }})">
                                                                Reject
                                                            </button>
                                                        </td>

                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Reject Modal -->
                            <div class="modal" id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); overflow: auto; z-index: 1050; backdrop-filter: blur(2px);">
                                <div class="modal-dialog" style="margin: 3% auto; max-width: 650px; animation: slideDown 0.3s ease-out;">
                                    <div class="modal-content" style="border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15); border-radius: 16px; background: #ffffff; overflow: hidden;">

                                        <!-- Modal Header -->
                                        <div class="modal-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 24px 32px; border: none; position: relative;">
                                            <h5 class="modal-title" id="rejectModalLabel" style="margin: 0; font-size: 1.5rem; font-weight: 600; letter-spacing: -0.025em; color: white;">Reject Request</h5>
                                            <button type="button" class="btn-close" onclick="closeRejectModal()"
                                                style="background: none; border: none; color: white; font-size: 24px; cursor: pointer; padding: 8px; border-radius: 50%; transition: background-color 0.2s; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"
                                                onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'"
                                                onmouseout="this.style.backgroundColor='transparent'">×</button>
                                        </div>


                                        <!-- Modal Body -->
                                        <div class="modal-body" style="padding: 32px; background: #ffffff;">
                                            <input type="hidden" id="rejectRequestId">
                                            <div style="margin-bottom: 24px;">
                                                <label for="rejectionNote" style="display: block; margin-bottom: 8px; color: #1f2937; font-weight: 600; font-size: 1rem;">Rejection Note</label>
                                                <textarea class="form-control" id="rejectionNote" name="note" rows="3" placeholder="Enter note for rejection..." required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; line-height: 1.5; resize: vertical; transition: border-color 0.2s; background: #ffffff; color: #1f2937;" onfocus="this.style.borderColor='#ef4444'; this.style.outline='none';" onblur="this.style.borderColor='#e5e7eb';"></textarea>
                                            </div>
                                        </div>

                                        <!-- Modal Footer -->
                                        <div class="modal-footer" style="padding: 24px 32px; background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); border: none; display: flex; justify-content: space-between; align-items: center;">
                                            <button type="button" onclick="closeRejectModal()" style="padding: 10px 24px; background: #6b7280; color: white; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.2s; font-size: 0.95rem;" onmouseover="this.style.background='#4b5563'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#6b7280'; this.style.transform='translateY(0)'">Close</button>
                                            <a href="#" id="finalRejectUrl" style="padding: 10px 20px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.2s; font-size: 0.95rem; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(239, 68, 68, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(239, 68, 68, 0.2)'">Reject</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                             <!-- Approve Modal -->
                             <div class="modal" id="approveModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); overflow: auto; z-index: 1050; backdrop-filter: blur(2px);">
                                <div class="modal-dialog" style="margin: 3% auto; max-width: 650px; animation: slideDown 0.3s ease-out;">
                                    <div class="modal-content" style="border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15); border-radius: 16px; background: #ffffff; overflow: hidden;">

                                        <!-- Modal Header -->
                                            <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 24px 32px; border: none; position: relative;">
                                                <h5 class="modal-title" id="approveModalLabel" style="margin: 0; font-size: 1.5rem; font-weight: 600; letter-spacing: -0.025em; color: white;">Buy Request Details</h5>
                                                <button type="button" class="btn-close" onclick="closeApproveModal()"
                                                    style="background: none; border: none; color: white; font-size: 24px; cursor: pointer; padding: 8px; border-radius: 50%; transition: background-color 0.2s; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"
                                                    onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'"
                                                    onmouseout="this.style.backgroundColor='transparent'">×</button>
                                            </div>


                                        <!-- Modal Body -->
                                        <div class="modal-body" style="padding: 32px; background: #ffffff;">
                                            <input type="hidden" id="approveRequestId">

                                            <!-- Investor Details -->
                                            <div style="margin-bottom: 24px;">
                                                <h6 style="margin: 0 0 12px 0; color: #1f2937; font-size: 1.1rem; font-weight: 600; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
                                                    Investor Details
                                                </h6>
                                                <div style="margin-left: 0;">
                                                    <p style="margin: 8px 0; display: flex; justify-content: space-between;">
                                                        <strong style="color: #4b5563;">Name:</strong>
                                                        <span id="investorName" style="color: #1f2937;">-</span>
                                                    </p>
                                                    <p style="margin: 8px 0; display: flex; justify-content: space-between;">
                                                        <strong style="color: #4b5563;">Address:</strong>
                                                        <span id="investorAddress" style="color: #1f2937; text-align: right; max-width: 60%;">-</span>
                                                    </p>
                                                    <p style="margin: 8px 0; display: flex; justify-content: space-between;">
                                                        <strong style="color: #4b5563;">Country:</strong>
                                                        <span id="investorCountry" style="color: #1f2937;">-</span>
                                                    </p>
                                                    <p style="margin: 8px 0; display: flex; justify-content: space-between;">
                                                        <strong style="color: #4b5563;">Phone:</strong>
                                                        <span id="investorPhone" style="color: #1f2937;">-</span>
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Property Details -->
                                            <div style="margin-bottom: 24px;">
                                                <h6 style="margin: 0 0 12px 0; color: #1f2937; font-size: 1.1rem; font-weight: 600; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
                                                    Property / Asset
                                                </h6>
                                                <p style="margin: 8px 0; display: flex; justify-content: space-between;">
                                                    <strong style="color: #4b5563;">Name:</strong>
                                                    <span id="propertyName" style="color: #1f2937;">-</span>
                                                </p>
                                            </div>

                                            <!-- Custody Details -->
                                            <div style="margin-bottom: 24px;">
                                                <h6 style="margin: 0 0 12px 0; color: #1f2937; font-size: 1.1rem; font-weight: 600; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
                                                    Custody Details
                                                </h6>
                                                <div style="margin: 8px 0; display: flex; justify-content: space-between;">
                                                    <label style="color: #4b5563; font-weight: 600;">Wallet Type:</label>
                                                    <div id="wallet_type" style="color: #1f2937;">-</div>
                                                </div>
                                                <div id="externalAddressWrapper" style="display: none;">
                                                    <div style="margin: 8px 0; display: flex; justify-content: space-between;">
                                                        <label style="color: #4b5563; font-weight: 600;">External Address:</label>
                                                        <div id="externalAddress" style="color: #1f2937; font-family: monospace; font-size: 0.9rem; text-align: right; max-width: 60%; word-break: break-all;">-</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Payment Details -->
                                            <div style="margin-bottom: 24px;">
                                                <h6 style="margin: 0 0 12px 0; color: #1f2937; font-size: 1.1rem; font-weight: 600; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
                                                    Payment Details
                                                </h6>
                                                <p style="margin: 8px 0; display: flex; justify-content: space-between;">
                                                    <strong style="color: #4b5563;">Method:</strong>
                                                    <span id="paymentMode" style="color: #1f2937;">-</span>
                                                </p>
                                                <p id="transactionIdLabel" style="display: none; margin: 8px 0;">
                                                    <div style="display: flex; justify-content: space-between;">
                                                        <strong style="color: #1f2937;">Transaction ID/Hash:</strong>
                                                        <a id="transactionIdLink"
                                                            href="#"
                                                            target="_blank"
                                                            style="color: #1f2937; font-family: monospace; font-size: 0.9rem; text-align: right; max-width: 60%; word-break: break-all; text-decoration: underline;">
                                                            -
                                                        </a>

                                                    </div>
                                                </p>
                                                <img id="paymentProofImage" src="" alt="Payment Proof" style="max-width: 100%; height: auto; border: 2px solid #e5e7eb; border-radius: 8px; margin-top: 16px; display: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                            </div>

                                            <!-- Token Details -->
                                            <div style="margin-bottom: 24px;">
                                                <h6 style="margin: 0 0 12px 0; color: #1f2937; font-size: 1.1rem; font-weight: 600; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
                                                    Token Details
                                                </h6>
                                                <div style="margin: 8px 0; display: flex; justify-content: space-between;">
                                                    <label style="color: #4b5563; font-weight: 600;">Available Tokens:</label>
                                                    <div id="availableTokensLabel" style="color: #1f2937;">--</div>
                                                </div>
                                                <div style="margin: 8px 0; display: flex; justify-content: space-between;">
                                                    <label style="color: #4b5563; font-weight: 600;">Requested Tokens:</label>
                                                    <div id="requestedTokensLabel" style="color: #1f2937;">--</div>
                                                </div>

                                                <div id="availabilityMessage" style="padding: 12px 16px; background: rgba(16, 185, 129, 0.1); color: #065f46; border-radius: 8px; font-weight: 500; border-left: 3px solid #10b981; margin-top: 16px;">
                                                    Token will be transferred.
                                                </div>
                                                <div id="sellRemainingMessage" style="display: none; padding: 12px 16px; background: rgba(245, 158, 11, 0.1); color: #92400e; border-radius: 8px; font-weight: 500; border-left: 3px solid #f59e0b; margin-top: 16px;">
                                                    Only <span id="remainingTokens"></span> tokens remain. Transfer remaining?
                                                </div>
                                            </div>

                                            <!-- Approval Note -->
                                            <div style="margin-bottom: 0;">
                                                <label for="approveNote" style="display: block; margin-bottom: 8px; color: #1f2937; font-weight: 600; font-size: 1rem;">Note</label>
                                                <textarea class="form-control" id="approveNote" name="note" rows="3" placeholder="Enter note for approval..." style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; line-height: 1.5; resize: vertical; transition: border-color 0.2s; background: #ffffff; color: #1f2937;" onfocus="this.style.borderColor='#3b82f6'; this.style.outline='none';" onblur="this.style.borderColor='#e5e7eb';"></textarea>
                                            </div>
                                        </div>

                                        <!-- Modal Footer -->
                                        <div class="modal-footer" style="padding: 24px 32px; background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); border: none; display: flex; justify-content: space-between; align-items: center;">
                                            <button type="button" onclick="closeApproveModal()" style="padding: 10px 24px; background: #6b7280; color: white; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.2s; font-size: 0.95rem;" onmouseover="this.style.background='#4b5563'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#6b7280'; this.style.transform='translateY(0)'">Close</button>
                                            <div style="display: flex; gap: 12px;">
                                                <button type="button" onclick="triggerRejectFromApprove()" style="padding: 10px 20px; background: #ef4444; color: white; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.2s; font-size: 0.95rem;" onmouseover="this.style.background='#dc2626'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#ef4444'; this.style.transform='translateY(0)'">Reject</button>
                                                <button type="button" id="approveTransferButton" style="padding: 10px 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.2s; font-size: 0.95rem; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(16, 185, 129, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(16, 185, 129, 0.2)'">Approve and Transfer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- END content-page -->

<script>

    let selectedRequestId = null;
    function triggerRejectFromApprove() {
        const requestId = document.getElementById('approveRequestId').value;
        openRejectModal(requestId);
    }
    function openRejectModal(requestId) {
        selectedRequestId = requestId;
        document.getElementById('rejectRequestId').value = requestId;
        document.getElementById('rejectionNote').value = '';
        document.getElementById('finalRejectUrl').setAttribute('href', '#'); // Reset
        document.getElementById('rejectModal').style.display = 'block';
        document.getElementById('approveModal').style.display = 'none';

    }

    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';

    }

    document.getElementById('rejectionNote').addEventListener('input', function () {
        const note = document.getElementById('rejectionNote').value.trim();
        const baseUrl = `/issuer/update_buy_request/${selectedRequestId}/Cancelled`;
        const finalUrl = `${baseUrl}?note=${encodeURIComponent(note)}`;
        document.getElementById('finalRejectUrl').setAttribute('href', finalUrl);
    });


    function openApproveModal(data) {
        console.log(data);
        selectedRequestId = data.request.id;

        // Investor details
        document.getElementById('investorName').innerText = data?.user?.name || 'N/A';

        // user_details is the investor's identity record, which is null until they
        // complete their profile. Reading through it unguarded threw and stopped
        // openApproveModal() before the modal opened, so Approve did nothing.
        document.getElementById('investorAddress').innerText = data?.user_details?.address_line_1 || 'N/A';
        document.getElementById('investorCountry').innerText = data?.user_details?.residence || 'N/A';
        const countryCode = data?.user_details?.primary_country_code || '';
        const phone = data?.user_details?.primary_phone || '';
        document.getElementById('investorPhone').innerText = (countryCode + phone) || 'N/A';



        // Property/Asset
        document.getElementById('propertyName').innerText = data?.request?.property?.propertyName || 'N/A';

        // Custody info
        document.getElementById('wallet_type').innerText = data?.request?.wallet_type || 'N/A';

        // Payment details
        document.getElementById('paymentMode').innerText = (data?.request?.payment_mode || '').replace(/_/g, ' ') || 'N/A';
        const img = document.getElementById('paymentProofImage');
        if (img) {
            if (data.payment_proof_img_url) {
                img.src = data.payment_proof_img_url;
                img.style.display = 'block'; // show image
            } else {
                img.style.display = 'none'; // hide if no image
            }
        }




        // Set transaction link and text
        const transactionLink = document.getElementById('transactionIdLink');
        const transactionId = data.request.payment_reference_id || '-';

        // Update text
        transactionLink.textContent = transactionId;

        // Use the img()-processed URL from API response
        if (data.payment_proof_img_url && transactionId !== '-') {
            transactionLink.href = data.payment_proof_img_url; // already processed by img() on backend
            transactionLink.target = "_blank";
            transactionLink.style.pointerEvents = "auto";
        } else {
            transactionLink.href = "#";
            transactionLink.removeAttribute("target");
            transactionLink.style.pointerEvents = "none";
        }


        // Token details
        document.getElementById('approveRequestId').value = data.request.id;
        document.getElementById('availableTokensLabel').innerText = data?.request?.usercontract?.tokenbalance ?? 'N/A';
        document.getElementById('requestedTokensLabel').innerText = data?.request?.token_acquire ?? 'N/A';
        document.getElementById('approveNote').value = '';

        const availabilityMessage = document.getElementById('availabilityMessage');
        const sellRemainingMessage = document.getElementById('sellRemainingMessage');
        const remainingTokensSpan = document.getElementById('remainingTokens');

        if (data.request.token_acquire <= data.request.usercontract.tokenbalance) {
            availabilityMessage.innerText = `Transfer ${data.request.token_acquire} tokens.`;
            sellRemainingMessage.style.display = 'none';
        } else if (data.request.usercontract.tokenbalance > 0) {
            availabilityMessage.innerText = `Transfer only ${data.request.usercontract.tokenbalance} tokens.`;
            remainingTokensSpan.innerText = data.request.usercontract.tokenbalance;
            sellRemainingMessage.style.display = 'block';
        } else {
            availabilityMessage.innerText = `No tokens available to transfer.`;
            sellRemainingMessage.style.display = 'none';
        }

        document.getElementById('approveModal').style.display = 'block';
        document.getElementById('rejectModal').style.display = 'none';
    }


    function closeApproveModal() {
        document.getElementById('approveModal').style.display = 'none';

    }

    document.getElementById('approveTransferButton').addEventListener('click', function () {
        const requestId = document.getElementById('approveRequestId').value;
        const note = document.getElementById('approveNote').value.trim();
        const baseUrl = `/issuer/update_buy_request/${requestId}/Approved`;
        const finalUrl = `${baseUrl}?note=${encodeURIComponent(note)}`;
        window.location.href = finalUrl;
    });
</script>


@endsection

