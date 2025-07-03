@if($label)
    <label 
        class="col-lg-4 col-form-label fw-semibold fs-6 {{ $required ? 'required' : '' }}"
        for="{{ $inputId ?? $inputName }}"
        >
        {{ $label }}
    </label>
@endif


<div class="col-lg-4 fv-row">
    <select 
        class="form-select form-select-lg form-select-solid"
        data-control="select2"
        data-hide-search="true"
        data-placeholder="{{ $label }}"
        name="{{ $inputName }}{{ $multiple ? '[]' : '' }}"
        id="{{ $inputId ?? $inputName }}"
        {{ $multiple ? 'multiple' : '' }}
    >
    <option selected disabled></option>
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
</div>

@if($note)
    <div class="form-text">{{ $note }}</div>
@endif

@if($description)
    <div class="text-muted fs-7">{{ $description }}</div>
@endif
