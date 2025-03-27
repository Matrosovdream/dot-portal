<div class="fv-row mb-2">
    <div class="dropzone" id="kt_ecommerce_add_{{ $inputName }}">

        <input 
            type="file" 
            id="{{ $inputName }}_input" 
            name="{{ $inputName }}" 
            hidden 
            accept="{{ $accept  }}"
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
    </div>
</div>
<div class="text-muted fs-7">
    {{ $description }}
</div>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropzone = document.getElementById('kt_ecommerce_add_{{ $inputName }}');
    const fileInput = document.getElementById('{{ $inputName }}_input');

    // Click dropzone to trigger file input
    dropzone.addEventListener('click', function () {
        fileInput.click();
    });

    // Handle file selection
    fileInput.addEventListener('change', function () {
        const file = fileInput.files[0];

        // Clear any previous preview
        let existingPreview = document.querySelector('.file-preview');
        if (existingPreview) existingPreview.remove();

        if (!file) return;

        // Create preview container
        const previewContainer = document.createElement('div');
        previewContainer.className = 'file-preview mt-10';

        // Show preview (image or file name)
        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = file.name;
            img.style.maxWidth = '100%';
            img.style.borderRadius = '8px';
            img.onload = () => URL.revokeObjectURL(img.src);
            previewContainer.appendChild(img);
        } else if (file.type === 'application/pdf') {
            const p = document.createElement('p');
            p.textContent = `📄 ${file.name}`;
            previewContainer.appendChild(p);
        } else {
            const p = document.createElement('p');
            p.textContent = 'Unsupported file type';
            previewContainer.appendChild(p);
        }

        // Append preview to dropzone
        dropzone.appendChild(previewContainer);
    });
});
</script>
