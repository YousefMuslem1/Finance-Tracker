@extends('layouts.app')

@section('content')
    <h1>Categories Management</h1>
    <hr>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newCategoryModal">
        New Category
    </button>
    <x-flash-success-message />
    <div class="my-2"></div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Category</th>
                <th scope="col">type</th>
                <th scope="col">Main Category</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $category->name }}</td>
                    <td class="text-{{ $category->type == 'income' ? 'success' : 'danger' }}">
                        {{ ucfirst($category->type) }}
                    </td>
                    <td>{{ $category->parent ? $category->parent->name : '-' }}</td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm btn-edit" data-bs-toggle="modal"
                            data-bs-target="#editCategoryModal" data-id="{{ $category->id }}"
                            data-name="{{ $category->name }}" data-type="{{ $category->type }}"
                            data-parent-id="{{ $category->parent_id }}"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $category->id }}"
                            data-name="{{ $category->name }}" data-url="{{ route('categories.destroy', $category->id) }}">
                            <i class="fa fa-trash "></i>
                        </button>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $categories->links() }}
    </div>
    <!-- new category modal -->
    <x-modal id="newCategoryModal" title="Add new Category" formId="newCategoryForm" :isDisabled="true">
        <form id="newCategoryForm" method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div class="mb-3">
                <label for="categoryName" class="form-label">Category Name</label>
                <input type="text" class="form-control" name="name" id="categoryName" required>
                <small id="nameFeedback" class="text-danger"></small>

            </div>
            <div class="mb-3">
                <label for="categoryType" class="form-label">Type</label>
                <select class="form-select" name="type" id="categoryType" required>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="parentCategory" class="form-label">Main Category</label>
                <select class="form-select" name="parent_id" id="parentCategory">
                    <option value="">Nothing</option>
                    @foreach ($parentCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" id="csrf_token" value="{{ csrf_token() }}">
            <input type="hidden" id="check_name_route" value="{{ route('categories.check-name') }}">
        </form>
    </x-modal>

    <!-- edit category modal -->
    <x-modal id="editCategoryModal" title="Edit Category" formId="editCategoryForm" :isDisabled="true">
        <form id="editCategoryForm" method="POST" action="{{ route('categories.update', ['category' => ':id']) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="editCategoryName" class="form-label">Category Name</label>
                <input type="text" class="form-control" name="name" id="editCategoryName" required>
                <small id="editNameFeedback" class="text-danger"></small>
            </div>
            <div class="mb-3">
                <label for="editCategoryType" class="form-label">Type</label>
                <select class="form-select" name="type" id="editCategoryType" required>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="editParentCategory" class="form-label">Main Category</label>
                <select class="form-select" name="parent_id" id="editParentCategory">
                    <option value="">Nothing</option>
                    @foreach ($parentCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" id="editCategoryId" name="id">
            <input type="hidden" id="csrf_token_edit" value="{{ csrf_token() }}">
            <input type="hidden" id="check_name_route_edit" value="{{ route('categories.check-name') }}">
            <input type="hidden" id="editCategoryUpdateRoute"
                data-url="{{ route('categories.update', ['category' => ':id']) }}">
        </form>
    </x-modal>
@endsection
@section('scripts')
@endsection
