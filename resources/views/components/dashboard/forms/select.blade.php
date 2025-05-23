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
        <option selected disabled></option>
        @foreach($options as $option)
            <option value="{{ $option['value'] }}" @if($value == $option['value']) selected @endif>
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
