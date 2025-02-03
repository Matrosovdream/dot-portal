<ul class="list-group apply-offer-list">
    @foreach($product['Model']->offers as $offer)
        <li class="">
            <div class="form-check  @if($loop->first) active @endif">

                <input class="form-check-input" type="radio" name="offer_id" id="offer-{{ $offer->id }}"
                    value="{{ $offer->id }}" data-price="{{ $offer->price }}" @if($loop->first) checked @endif>

                <label class="form-check label" for="offer-{{ $offer->id }}">
                    <h5>{{ $offer->name }}</h5>
                    <p>{{ $offer->description }}</p>
                    <p>{{ $offer->price }} {{ $currency }}</p>
                </label>
            </div>
        </li>
    @endforeach
</ul>

<!-- Extra services with checkboxes -->
<div class="extra-services-list mt-25">
    <h5 class="mb-15">{{ __('Extra services') }}</h5>
    <ul class="list-group">
        @foreach($extras['optional'] as $extra)
            <li class="list-group-item">
                <div class="form-check
                    @if($extra->price > 0) has-price @endif">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        name="extra_ids[]"
                        id="extra-{{ $extra->id }}" 
                        value="{{ $extra->id }}" 
                        data-price="{{ $extra->price * count($travellers) }}"
                        {{ isset( $cart['extras'][ $extra->id ] ) ? 'checked' : '' }}
                        >
                    <label class="form-check
                        label" for="extra-{{ $extra->id }}">
                        <h5>{{ $extra->name }}</h5>
                        <p>{{ $extra->description }}</p>
                        @if($extra->price > 0)
                            <p>{{ $extra->price * count($travellers) }} {{ $currency }}</p>
                        @endif  
                    </label>
                </div>
            </li>
        @endforeach
    </ul>
</div>
