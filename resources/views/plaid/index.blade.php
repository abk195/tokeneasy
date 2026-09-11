@extends($layout)

@section('content')
    <div class="page-content">
        <div class="header-breadcrumbs">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1><small>Plaid Account</small></h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="row">
                <!-- Connect Bank Account Panel -->
                <div class="col-md-12">
                    <div class="panel panel-primary card card-primary">
                        {{-- <div class="panel-heading card-header">
                            <h3 class="panel-title card-title">{{ __('Connect Bank Account') }}</h3>
                        </div> --}}

                        <div class="panel-body card-body">
                            <div id="link-result" class="alert" style="display: none;"></div>

                                <p>
                                    Connect your bank account securely using Plaid to access your financial data.
                                </p>
                                <a href="{{ route('plaid.connect') }}" class="btn btn-primary">
                                    <i class="fa fa-university"></i>&nbsp;
                                    Connect Bank Account
                                </a>
                        </div>
                    </div>
                </div>

                <!-- Connected Accounts Section -->
                <div class="col-md-12">
                    <div id="connected-accounts">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @foreach ($plaidItems as $plaidItem)
                            <div class="panel panel-default card">
                                <div class="panel-body card-body">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <h3>
                                                <i class="fa fa-university text-primary"></i>&nbsp;
                                                {{ $plaidItem->title }} ({{$plaidItem->institution->name}})
                                            </h3>
                                            <p class="text-muted">
                                                {{ $plaidItem->description }}
                                            </p>
                                        </div>
                                        <div class="col-sm-4 text-right">
                                            <div class="btn-group">
                                                <a href="{{ route('plaid.account-details', $plaidItem->id) }}"
                                                   class="btn btn-default btn-secondary">
                                                    <i class="fa fa-eye"></i>&nbsp;
                                                    View Account
                                                </a>
                                                <button type="button"
                                                   class="btn btn-danger"
                                                   data-toggle="modal" data-bs-toggle="modal"
                                                   data-target="#deleteModal{{ $plaidItem->id }}" data-bs-target="#deleteModal{{ $plaidItem->id }}">
                                                    <i class="fa fa-trash"></i>&nbsp;
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Confirmation Modal -->
                            <div class="modal fade" id="deleteModal{{ $plaidItem->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Confirm Removal</h4>
                                            <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to remove the connection to {{ $plaidItem->title }}?</p>
                                            <p class="text-muted">This action cannot be undone.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                                            <a href="{{ route('plaid.remove-item', ['itemId' => $plaidItem->id]) }}"
                                               class="btn btn-danger">
                                                <i class="fa fa-trash"></i>&nbsp;
                                                Remove Account
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .header-breadcrumbs {
            padding: 15px 0;
            background-color: #f5f5f5;
            margin-bottom: 20px;
            border-bottom: 1px solid #e5e5e5;
        }

        .content {
            padding: 0 15px;
        }

        .btn-group .btn {
            margin-left: 5px;
        }

        .well {
            margin-bottom: 0;
        }

        h1 small {
            display: block;
            font-size: 65%;
            color: #777;
        }

        .modal-header {
            border-bottom: 1px solid #e5e5e5;
            padding: 15px;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            border-top: 1px solid #e5e5e5;
            padding: 15px;
        }

        @media (max-width: 767px) {
            .btn-group .btn {
                margin-top: 5px;
                margin-left: 0;
            }
        }
    </style>
@endsection
