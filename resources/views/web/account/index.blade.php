@extends('web.layouts.app')

@section('content')

<div class="container my-4">

    <h2 class="mb-25">{{ __('Personal area') }}</h2>
    <hr/>

    <div class="row g-3 mt-10">
        <div class="col-md-4">
            <a href="{{ route('web.account.orders') }}">
                <div class="card card-custom">
                    <div class="icon-custom"><i class="bi bi-file-earmark-text"></i></div>
                    <h3>{{ __('My Orders') }}</h3>
                    <p>{{ __('View your orders and travel documents') }}</p>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('web.account.settings') }}">
                <div class="card card-custom">
                    <div class="icon-custom"><i class="bi bi-shield-lock"></i></div>
                    <h3>{{ __('Security & Privacy') }}</h3>
                    <p>{{ __('Update your password') }}</p>
                </div>
            </a>
        </div>

    </div>
</div>

<style>
    .card-custom {
        border-radius: 12px;
        padding: 20px;
        text-align: left;
        color: #003d4a;
        background-color: #f9fcfc;
    }

    .card-custom h3 {
        font-size: 1.2em;
        margin: 10px 0;
    }

    .card-custom p {
        font-size: 0.9em;
        color: #6c757d;
    }

    .icon-custom {
        font-size: 1.8em;
        color: #003d4a;
    }
</style>



@endsection