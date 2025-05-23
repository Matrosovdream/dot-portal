<div class="col-md-6 mb-5">
    @if($label)
        <label for="{{ $inputId ?? $inputName }}" class="form-label {{ $required ? 'required' : '' }} fs-5 fw-semibold mb-2">
            {{ $label }}
        </label>
    @endif

    <input
        type="text"
        class="form-control form-control-solid datepicker"
        id="{{ $inputId ?? $inputName }}"
        name="{{ $inputName }}"
        value="{{ $value ?? '' }}"
        {{ $required ? 'required' : '' }}
    >

    @if($note)
        <div class="form-text">{{ $note }}</div>
    @endif

    @if($description)
        <div class="text-muted fs-7">{{ $description }}</div>
    @endif
</div>
