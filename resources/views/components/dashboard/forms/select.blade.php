<div class="mb-5">
    @if($label)
        <label for="{{ $inputId ?? $inputName }}" class="{{ $required ? 'required' : '' }} fs-5 fw-semibold mb-2">
            {{ $label }}
        </label>
    @endif

    <select
        class="form-select form-select-solid"
        data-control="select2"
        data-hide-search="true"
        data-placeholder="{{ $label }}"
        name="{{ $inputName }}{{ $multiple ? '[]' : '' }}"
        id="{{ $inputId ?? $inputName }}"
        {{ $required ? 'required' : '' }}
        {{ $multiple ? 'multiple' : '' }}
    >

        @if( !$multiple )
            <option selected disabled></option>
        @endif

        @foreach($options as $option)
            <option 
                value="{{ $option['value'] }}" 
                @if($multiple)
                    @if(in_array($option['value'], $values)) selected @endif
                @else   
                    @if($value == $option['value']) selected @endif
                @endif
                >
                {{ $option['title'] }}
            </option>
        @endforeach
    </select>

    @if($note)
        <div class="form-text">{{ $note }}</div>
    @endif

    @if($description)
        <div class="text-muted fs-7">{{ $description }}</div>
    @endif
</div>
