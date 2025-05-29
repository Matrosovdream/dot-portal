<div class="fv-row mb-2">

    @if($title)
        <label for="{{ $inputId ?? $inputName }}" class="form-label {{ $required ? 'required' : '' }} fs-5 fw-semibold mb-2">
            {{ $title }}
        </label>
    @endif

    <div class="dropzone" id="kt_ecommerce_add_{{ $inputId }}">

        <input 
            type="file" 
            id="{{ $inputId }}_file" 
            name="{{ $inputName }}" 
            hidden 
            accept="{{ $accept }}"
        >

        <div class="dz-message needsclick">
            <i class="ki-duotone ki-file-up text-primary fs-3x">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
            <div class="ms-4">
                <h3 class="fs-5 fw-bold text-gray-900 mb-1">
                    {{ $label }}
                </h3>
                <span class="fs-7 fw-semibold text-gray-500">
                    {{ $note }}
                </span>
            </div>
        </div>

        {{-- Existing file preview --}}
        @if($value && isset($value['filename']))
            <div class="file-preview mt-10" id="existing_{{ $inputId }}">
                @if(str_starts_with($value['type'], 'image/'))
                    <img src="{{ $value['showUrl'] }}" alt="{{ $value['filename'] }}" style="max-width: 100%; border-radius: 8px;">
                @elseif($value['type'] === 'application/pdf')
                    <p>📄 {{ $value['filename'] }}</p>
                @else
                    <p>📁 {{ $value['filename'] }}</p>
                @endif

                <div class="mt-2">
                    <button type="button" class="btn btn-sm btn-danger me-2" id="remove_btn_{{ $inputId }}">Remove</button>
                    <button type="button" class="btn btn-sm btn-secondary d-none" id="restore_btn_{{ $inputId }}">Restore</button>
                </div>

                <input type="checkbox" name="{{ $inputName }}_remove" id="{{ $inputId }}_remove" hidden>
            </div>
        @endif
    </div>
</div>

@if($note)
    <div class="form-text">{{ $note }}</div>
@endif

@if($description)
    <div class="text-muted fs-7">{{ $description }}</div>
@endif


<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropzone = document.getElementById('kt_ecommerce_add_{{ $inputId }}');
    const fileInput = document.getElementById('{{ $inputId }}_file');

    // Prevent file input from triggering when clicking buttons
    dropzone.addEventListener('click', function (e) {
        const isActionButton = e.target.closest('button');
        if (!isActionButton) {
            fileInput.click();
        }
    });

    // Live file preview logic
    fileInput.addEventListener('change', function () {
        const file = fileInput.files[0];
        let existingPreview = document.querySelector('.file-preview');
        if (existingPreview) existingPreview.remove();
        if (!file) return;

        const previewContainer = document.createElement('div');
        previewContainer.className = 'file-preview mt-10';

        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = file.name;
            img.style.maxWidth = '100%';
            img.style.borderRadius = '8px';
            img.onload = () => URL.revokeObjectURL(img.src);
            previewContainer.appendChild(img);
        } else {
            const p = document.createElement('p');
            p.textContent = file.type === 'application/pdf' ? `📄 ${file.name}` : `Unsupported file type`;
            previewContainer.appendChild(p);
        }

        dropzone.appendChild(previewContainer);
    });

    // Remove/Restore toggle
    const removeBtn = document.getElementById('remove_btn_{{ $inputId }}');
    const restoreBtn = document.getElementById('restore_btn_{{ $inputId }}');
    const removeCheckbox = document.getElementById('{{ $inputId }}_remove');
    const previewBlock = document.getElementById('existing_{{ $inputId }}');

    if (removeBtn && restoreBtn && removeCheckbox && previewBlock) {
        removeBtn.addEventListener('click', function (e) {
            e.stopPropagation(); // Prevent triggering dropzone click
            removeCheckbox.checked = true;
            removeBtn.classList.add('d-none');
            restoreBtn.classList.remove('d-none');
            previewBlock.classList.add('opacity-50');
        });

        restoreBtn.addEventListener('click', function (e) {
            e.stopPropagation(); // Prevent triggering dropzone click
            removeCheckbox.checked = false;
            restoreBtn.classList.add('d-none');
            removeBtn.classList.remove('d-none');
            previewBlock.classList.remove('opacity-50');
        });
    }
});

</script>
