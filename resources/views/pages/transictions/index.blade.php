@extends('layouts.app')
@section('content')
    <h1>Transication Management</h1>
    <hr>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newTransictionModal">
        New Transiction
    </button>
    <x-flash-success-message />
    <div class="my-2"></div>
    <form method="GET" action="{{ route('transications.index') }}" class="mb-4">
        <div class="row g-3">
            <!-- Filter by Type -->
            <div class="col-lg-3 col-md-6">
                <label for="filterType" class="form-label">Type</label>
                <select class="form-select" name="type" id="filterType">
                    <option value="">All Types</option>
                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Income</option>
                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                </select>
            </div>

            <!-- Filter by Date Range -->
            <div class="col-lg-3 col-md-6">
                <label class="form-label">Date Range</label>
                <div class="input-group">
                    <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}"
                        placeholder="Start Date">
                    <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}"
                        placeholder="End Date">
                </div>
            </div>

            <!-- Filter by Category -->
            <div class="col-lg-3 col-md-6">
                <label for="filterCategory" class="form-label">Category</label>
                <select class="form-select" name="category_id" id="filterCategory">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by Amount Range -->
            <div class="col-lg-3 col-md-6">
                <label class="form-label">Amount Range</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="min_amount" value="{{ request('min_amount') }}"
                        placeholder="Min">
                    <input type="number" class="form-control" name="max_amount" value="{{ request('max_amount') }}"
                        placeholder="Max">
                </div>
            </div>

            <!-- Filter by Commission Range -->
            <div class="col-lg-3 col-md-6">
                <label class="form-label">Commission Range</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="min_commission"
                        value="{{ request('min_commission') }}" placeholder="Min">
                    <input type="number" class="form-control" name="max_commission"
                        value="{{ request('max_commission') }}" placeholder="Max">
                </div>
            </div>

            <!-- Submit and Reset Buttons -->
            <div class="col-lg-3 col-md-6 align-self-end">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    <a href="{{ route('transications.index') }}" class="btn btn-secondary w-100">Reset</a>

                </div>
            </div>
            <div class="col-lg-3 col-md-6 align-self-end">
                <div class="d-flex gap-2">
                    <a href="{{ route('transactions.export.pdf', request()->query()) }}" class="btn btn-success">Export to
                        PDF</a>
                </div>
            </div>
        </div>
    </form>


    <table class="table table-bordered" id="transactionsTable">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Type</th>
                <th scope="col">Category</th>
                <th scope="col">Amount</th>
                <th scope="col">Commission</th>
                <th scope="col">Total</th>
                <th scope="col">Description</th>
                <th scope="col">Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transictions as $transiction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-{{ $transiction->type == 'income' ? 'success' : 'danger' }}">{{ $transiction->type }}
                    </td>
                    <td class="text-{{ $transiction->type == 'income' ? 'success' : 'danger' }}">
                        {{ $transiction->category->name }}</td>
                    <td class="text-{{ $transiction->type == 'income' ? 'success' : 'danger' }}">
                        {{ $transiction->amount ?? 0 }}
                    </td>
                    <td class="text-{{ $transiction->type == 'income' ? 'success' : 'danger' }}">
                        {{ $transiction->commission->amount ?? '' }}</td>
                    <td class="text-{{ $transiction->type == 'income' ? 'success' : 'danger' }}">
                        {{ $transiction->totalAmount() ?? '' }}</td>
                    <td>{{ Str::limit($transiction->description, 10, '...') }}</td>
                    <td>{{ $transiction->created_at->format('d/m/Y') }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm btn-edit" data-id="{{ $transiction->id }}"
                            data-bs-toggle="modal" data-bs-target="#editTransactionModal"
                            data-description="{{ $transiction->description }}"
                            data-amount="{{ $transiction->amount ?? 0 }}" data-category="{{ $transiction->category_id }}"
                            data-type="{{ $transiction->type }}"
                            data-commission="{{ $transiction->commission->amount ?? 0 }}">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $transiction->id }}"
                            data-name="{{ $transiction->amount }}">
                            <i class="fa fa-trash"></i>
                        </button>

                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $transictions->links() }}
    </div>
    <!-- New transiction modal -->
    <x-modal id="newTransictionModal" title="Add new Category" formId="newTransictionModal" :isDisabled="false">
        <form id="newTransictionForm" method="POST" action="{{ route('transications.store') }}">
            @csrf
            @method('post')
            <div class="mb-3">
                <label for="categoryType" class="form-label">Type</label>
                <select class="form-select" name="type" id="categoryType" required>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="parentCategory" class="form-label">Category</label>
                <select class="form-select" name="category_id" id="parentCategory">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" step="0.001" class="form-control" name="amount" id="amount" required>
                <div id="amountFeedback" class="invalid-feedback"></div>
            </div>
            <div class="mb-2">
                <label for="commission" class="form-label">Commission</label>
                <input type="number" step="0.001" class="form-control" name="commission" id="commission">
            </div>
            <div class="form-group">
                <label for="amount" class="form-label">Description</label>
                <textarea name="description" class="form-control" id="description" rows="3"></textarea>
            </div>
    </x-modal>
    <!-- Edit Transaction Modal -->
    <x-modal id="editTransactionModal" title="Edit Transaction" formId="editTransactionForm">
        <form action="">

        </form>
        <form id="editTransactionForm" method="POST"
            action="{{ route('transications.update', ['transication' => ':id']) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="editTransactionAmount" class="form-label">Amount</label>
                <input type="number" class="form-control" name="amount" id="editTransactionAmount">
            </div>
            <div class="mb-3">
                <label for="editTransactionType" class="form-label">Type</label>
                <select class="form-select" name="type" id="editTransactionType">
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="editTransactionCategory" class="form-label">Category</label>
                <select class="form-select" name="category" id="editTransactionCategory">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="editTransactionCommission" class="form-label">Commission</label>
                <input type="number" class="form-control" name="commission" id="editTransactionCommission">
            </div>
            <div class="mb-3">
                <label for="editTransactionDescription" class="form-label">Description</label>
                <textarea type="text" class="form-control" name="description" id="editTransactionDescription"></textarea>
            </div>
            <input type="hidden" id="editTransactionId" name="id">
            <input type="hidden" id="csrf_token_edit" value="{{ csrf_token() }}">
            <input type="hidden" id="editTransactionUpdateRoute"
                data-url="{{ route('transications.update', ['transication' => ':id']) }}">
        </form>
    </x-modal>
@endsection
@section('scripts')
    <script></script>
@endsection
