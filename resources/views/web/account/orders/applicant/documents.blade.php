@extends('web.layouts.app')

@section('content')

<div class="container my-4">

    @include('web.account.orders.partials.backlink', ['url' => route('web.account.order', $order->id)])

    <h2 class="mb-25">
        {{ $order->getProduct()->name }} - {{ __('Documents') }}
    </h2>

    <div class="row">
        <div class="col-md-3">
            @include('web.account.orders.partials.sidebar')
        </div>

        <div class="col-md-9">
            <div class="card p-4">
                
                <div class="application-section-head mb-25">
                    <h3 class="card-title">{{ __('Documents') }}</h3>
                    <p class="card-text text-warning">{{ __('Passport page is required') }}</p>
                </div>

                <div class="row row-cols-1 row-cols-md-2 g-4">
                    @foreach($applicant->documents as $document)
                        <div class="col">
                            <div class="card shadow-sm h-100">
                                <!-- We display filename, path, description and remove form -->
                                <div class="card-body">
                                    <a href="{{ route('file.download', $document->document->id) }}">
                                        <h5 class="card-title">
                                            {{ $document->document->filename }}
                                        </h5>
                                    </a>
                                    @php /*
                                    <p class="card-text">Description: 
                                        @if( $document->document->description )
                                            {{ $document->document->description }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                    */ @endphp

                                    <form action="{{ route('web.account.order.applicant.document.delete', ['order_id' => $order->id, 'applicant_id' => $applicant->id, 'document_id' => $document->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-danger btn-remove mt-15">
                                                {{ __('Remove') }}
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <br/>
                <hr class="mb-15 mt-25">
                <h5 class="card-title">{{ __('Add new document') }}</h5>

                <!-- Documents form with the one field input file and description -->
                <form action="{{ route('web.account.order.applicant.documents.store', ['order_id' => $order->id, 'applicant_id' => $applicant->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" class="form-control" id="document" name="document">
                    </div>
                    @php /*
                    <div class="mb-3">
                        <label for="description" class="form-label">
                            {{ __('Document Description') }}
                        </label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    */ @endphp
                    <button type="submit" class="btn btn-primary">
                        {{ __('Add file') }}
                    </button>
                </form>

                

                
                
            </div>
        </div>
    </div>
</div>


<style>
    .card-body {
        background-color: rgb(248 249 249);
    }

    .card-status {
        background-color: #d1ecf1;
        color: #0c5460;
        border-radius: 10px;
        padding: 4px 8px 2px 8px;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .card-progress {
        background-color: #e9ecef;
        border-radius: 50%;
        padding: 5px;
        font-size: 0.8rem;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-country img {
        width: 20px;
        margin-right: 5px;
    }

    .btn-arrow {
        background: linear-gradient(90deg, #00d7b0, #00e65b);
        color: white;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        border: none;
    }

    .card-text {
        font-size: 15px;
    }
</style>

@endsection