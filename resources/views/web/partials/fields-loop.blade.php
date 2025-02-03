
@foreach($fields as $key=>$field)

    @php

    if( isset( $values[ $field['id'] ] ) ) {
        if( is_array($values[ $field['id'] ]) ) {
            $value = $values[ $field['id'] ]['value'] ?? '';
        } else {
            $value = $values[ $field['id'] ];
        }
    } else {
        $value = '';
    }

    switch( $entity ) {
        case 'order':
            $fieldName = 'fields['.$field['id'].']';
            break;
        case 'traveller':
            $fieldName = 'travellers['.$field['id'].'][]';
            break;
    }


    // Set the field id
    $field_id = 'field-'.$field['slug'];

    // Add the traveller index if it's a traveller field
    if( isset( $travellerIndex ) ) {
        $field_id .= '-'.$travellerIndex;
    }

    @endphp

    @if($field['type'] == 'text')

        <div class="mb-3 xb-item--field field-block-{{ $field['slug'] }}">
            <label for="field-{{ $field['slug'] }}" class="form-label  w-100">
                {{ $field['title'] }} {{ $field['required'] ? '*' : '' }}
            </label>
            <div>
                <input 
                    type="text" 
                    class="form-control w-75 {{ $field['classes'] ?? '' }} {{ $field['required'] ? 'required' : '' }}" 
                    id="{{ $field_id }}" name="{{ $fieldName }}"
                    value="{{ $value ?? '' }}"
                    placeholder="{{ $field['placeholder'] ?? '' }}"
                    >
            </div>
            @if( isset($field['field']['icon']) ) 
                <span class="icon">
                    <img src="{{ asset('/user/assets/img/icon/'.$field['field']['icon']) }}" alt="">
                </span>
            @endif
            
        </div>

    @endif

    @if($field['type'] == 'phone')

        <div class="mb-3 xb-item--field field-block-{{ $field['slug'] }}">
            <label for="field-{{ $field['slug'] }}" class="form-label  w-100">
                {{ $field['title'] }} {{ $field['required'] ? '*' : '' }}
            </label>
            <div>
                <input 
                    type="text" 
                    class="form-control w-75  {{ $field['classes'] ?? '' }} {{ $field['required'] ? 'required' : '' }}" 
                    id="{{ $field_id }}" name="{{ $fieldName }}"
                    value="{{ $value ?? '' }}"
                    placeholder="{{ $field['placeholder'] ?? '' }}"
                    >
            </div>
            @if( isset($field['field']['icon']) ) 
                <span class="icon">
                    <img src="{{ asset('/user/assets/img/icon/'.$field['field']['icon']) }}" alt="">
                </span>
            @endif
            
        </div>

    @endif

    @if($field['type'] == 'email')

        <div class="mb-3 xb-item--field field-block-{{ $field['slug'] }}">
            <label for="field-{{ $field['slug'] }}" class="form-label  w-100">
                {{ $field['title'] }} {{ $field['required'] ? '*' : '' }}
            </label>
            <div>
                <input 
                    type="email" 
                    class="form-control w-75 {{ $field['classes'] ?? '' }}" 
                    id="{{ $field_id }}" name="{{ $fieldName }}"
                    value="{{ $value }}"
                    placeholder="{{ $field['placeholder'] ?? '' }}"
                    >
                @if( isset($field['field']['icon']) ) 
                    <span class="icon">
                        <img src="{{ asset('/user/assets/img/icon/'.$field['field']['icon']) }}" alt="">
                    </span>
                @endif
            </div>
            @if( isset($field['field']['tooltip']) ) 
                <p class="form-note">
                    {{ $field['field']['tooltip'] }}
                </p>
            @endif

        </div>

        

    @endif

    @if($field['type'] == 'textarea')

        <div class="mb-3 xb-item--field field-block-{{ $field['slug'] }}">
            <label for="field-{{ $field['slug'] }}" class="form-label  w-100">
                {{ $field['title'] }} {{ $field['required'] ? '*' : '' }}
            </label>
            <textarea 
                class="form-control w-75 {{ $field['classes'] ?? '' }} {{ $field['required'] ? 'required' : '' }}" 
                id="{{ $field_id }}" 
                name="{{ $fieldName }}"
                placeholder="{{ $field['placeholder'] ?? '' }}"
                >{{ $value }}</textarea>
            @if( isset($field['field']['icon']) ) 
                <span class="icon">
                    <img src="{{ asset('/user/assets/img/icon/'.$field['field']['icon']) }}" alt="">
                </span>
            @endif
        </div>

        @if( isset($field['field']['tooltip']) ) 
            <p class="form-note">
                {{ $field['field']['tooltip'] }}
            </p>
        @endif

    @endif

    @if($field['type'] == 'date')

        <div class="mb-3 xb-item--field field-block-{{ $field['slug'] }}">
            <label for="field-{{ $field['slug'] }}-{{ $key }}" class="form-label  w-100">
                {{ $field['title'] }} {{ $field['required'] ? '*' : '' }}
            </label>
            <div>
                <input 
                    type="text" 
                    class="form-control w-50 {{ $field['classes'] ?? '' }} {{ $field['required'] ? 'required' : '' }}" 
                    id="{{ $field_id }}-{{ $key }}" 
                    name="{{ $fieldName }}" 
                    value="{{ $value ?? '' }}"
                    >
                @if( isset($field['field']['icon']) ) 
                    <span class="icon">
                        <img src="{{ asset('/user/assets/img/icon/'.$field['field']['icon']) }}" alt="">
                    </span>
                @endif
            </div>
            @if( isset($field['field']['tooltip']) ) 
                <p class="form-note">
                    {{ $field['field']['tooltip'] }}
                </p>
            @endif
        </div>

    @endif

    @if($field['type'] == 'select' || $field['type'] == 'reference')

        <div class="mb-3 xb-item--field field-block-{{ $field['slug'] }}">
            <label for="field-{{ $field['slug'] }}-{{ $key }}" class="form-label  w-100">
                {{ $field['title'] }} {{ $field['required'] ? '*' : '' }}
            </label>
            <div class="w-75">
                <select 
                    class="select2 w-75 {{ $field['classes'] ?? '' }} {{ $field['required'] ? 'required' : '' }}" 
                    id="{{ $field_id }}-{{ $key }}" 
                    name="{{ $fieldName }}"
                    >
                    <option selected disabled>{{ $field['placeholder'] ?? '' }}</option>
                    @foreach($field['options'] as $option)

                    @php
                        $option['value'] = $option['id'] ?? $option['value'];
                    @endphp

                        <option 
                            value="{{ $option['value'] }}" 
                            @if( $option['value'] == $value ) selected @endif
                            >
                            {{ $option['title'] ?? $option['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if( isset($field['field']['tooltip']) ) 
                <p class="form-note">
                    {{ $field['field']['tooltip'] }}
                </p>
            @endif
        </div>

    @endif

    @if($field['type'] == 'file')

        <div class="mb-3 xb-item--field field-block-{{ $field['slug'] }}">
            <label for="field-{{ $field['slug'] }}" class="form-label  w-100">
                {{ $field['title'] }} {{ $field['required'] ? '*' : '' }}
            </label>
            @if( isset($field['value']) )
                <a href="{{ Storage::url($field['value']->path) }}" target="_blank">
                    {{ __('Download') }}
                </a>
            @endif    

            <input type="file" class="form-control w-75 {{ $field['classes'] ?? '' }} {{ $field['required'] ? 'required' : '' }}" id="{{ $field_id }}" name="{{ $fieldName }}">

        </div>

        @if( isset($field['field']['tooltip']) ) 
            <p class="form-note">
                {{ $field['field']['tooltip'] }}
            </p>
        @endif

    @endif

    @if($field['type'] == 'radio')

        <div class="mb-3 xb-item--field field-block-{{ $field['slug'] }}">
            <label for="field-{{ $field['slug'] }}" class="form-label">
                {{ $field['title'] }} {{ $field['required'] ? '*' : '' }}
            </label>
            <div>
                @foreach($field['options'] as $option)
                    <div class="form-check">
                        <label class="form-check" for="field-{{ $field['slug'] }}-{{ $option['value'] }}">
                            <input class="form-check" type="radio" value="{{ $option['value'] }}"
                                id="{{ $field_id }}-{{ $option['value'] }}" name="{{ $fieldName }}"
                                @if( $option['value'] == $field['value'] ) checked @endif
                                >
                            {{ $option['title'] }}
                        </label>
                    </div>
                @endforeach
            </div>

            @if( isset($field['field']['tooltip']) ) 
                <p class="form-note">
                    {{ $field['field']['tooltip'] }}
                </p>
            @endif

        </div>

    @endif

    @if($field['type'] == 'checkbox')

        <div class="mb-3 xb-item--field field-block-{{ $field['slug'] }}">
            <label for="field-{{ $field['slug'] }}" class="form-label">
                {{ $field['title'] }} {{ $field['required'] ? '*' : '' }}
            </label>
            <div class="form-check">
                <input class="form-check w-75 {{ $field['classes'] ?? '' }} " type="checkbox" id="{{ $field_id }}"
                    @if( $field['value'] == 1 ) checked @endif
                    name="{{ $fieldName }}">
            </div>
            @if( isset($field['field']['tooltip']) ) 
                <p class="form-note">
                    {{ $field['field']['tooltip'] }}
                </p>
            @endif
        </div>

    @endif

@endforeach

