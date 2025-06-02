@if( isset($file) )

    <a href="{{ $file['downloadUrl'] }}">
        {{ $file['filename'] }} (download)
    </a>

@endif