@if( isset($file) )

    <a href="{{ $file['downloadUrl'] }}">
        {{ $file['filename'] }} (download)
    </a>

    @if( $isPreview ?? false && $file['extension'] != 'pdf' )

        {{-- Preview button and modal --}}

        <a href="#" class="btn btn-primary btn-sm flex-shrink-0 me-3"
            data-bs-toggle="modal" data-bs-target="#kt_modal_filepreview_{{ $file['id'] }}">
            Preview
        </a>
        @include('dashboard.modals.layout.file-preview', [
            'file_id' => $file['id']
        ])

    @endif

@endif