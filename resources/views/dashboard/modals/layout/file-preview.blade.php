<div class="modal fade" id="kt_modal_filepreview_{{ $file_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded">

            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-kt-modal-action-type="close">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>

            <div class="modal-body scroll-x px-10 px-lg-15 pt-0 pb-15">
                
                <img src="{{ route('file.show', $file_id) }}" class="img-fluid rounded-top w-100" />

            </div>

        </div>

    </div>

</div>

<!--
Usage case

<a 
    href="#" 
    class="btn btn-primary btn-sm flex-shrink-0 me-3" 
    data-bs-toggle="modal" 
    data-bs-target="#kt_modal_filepreview_{{ $file_id }}"
    >
    Preview
</a>
-->
