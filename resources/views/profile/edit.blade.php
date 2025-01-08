@extends('layouts.app')

@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('patch')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
                        <input class="form-control" id="name" name="name" type="text"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                        @error('name')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div>
                        <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                        <input class="form-control" id="email" name="email" type="email"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            value="{{ old('email', $user->email) }}" required autocomplete="username">
                        @error('email')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror

                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-1">
                Save
            </button>

        </form>
    </section>
    <hr>
    <section>
        <header class="mb-4">
            <h2>Update Password</h2>
            <p class="text-muted">Ensure your account is using a long, random password to stay secure.</p>
        </header>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-control"
                            autocomplete="current-password">
                        @if ($errors->updatePassword->has('current_password'))
                            <div class="text-danger small">{{ $errors->updatePassword->first('current_password') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" id="password" name="password" class="form-control"
                            autocomplete="new-password">
                        @if ($errors->updatePassword->has('password'))
                            <div class="text-danger small">{{ $errors->updatePassword->first('password') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                            autocomplete="new-password">
                        @if ($errors->updatePassword->has('password_confirmation'))
                            <div class="text-danger small">{{ $errors->updatePassword->first('password_confirmation') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="d-flex mt-1 align-items-center gap-3">
                <button type="submit" class="btn btn-primary">Save</button>

                @if (session('status') === 'password-updated')
                    <p class="text-success small mb-0">Saved.</p>
                @endif
            </div>
        </form>
    </section>
@endsection

{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div> 

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
