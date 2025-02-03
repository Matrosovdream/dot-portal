@extends('web.layouts.app')

@section('content')

<div class="container my-4">

    <h3 class="mb-25">{{ __('Security & Privacy') }}</h3>

    @if ($errors->any())
        <div class="alert alert-danger" role="danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success" role="success">
            {{ session('success') }}
        </div>
    @endif

    <form class="xb-item--form contact-from" method="POST" action="{{ route('web.account.settings') }}">
        @csrf

        <div class="row">
            <div class="col-lg-6">
                <div class="xb-item--field">
                    <input type="password" name="current_password" id="current_password" class="form-control"
                        placeholder="{{ __('Current password') }}">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="xb-item--field">
                    <input type="password" name="new_password" id="new_password" class="form-control"
                        placeholder="{{ __('New password') }}">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="xb-item--field">
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                        class="form-control" placeholder="{{ __('Confirm new password') }}">
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    {{ __('Update Password') }}
                </button>
            </div>
        </div>
    </form>

</div>


@endsection