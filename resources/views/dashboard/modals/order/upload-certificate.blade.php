<div class="modal fade" id="kt_modal_upload" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">

            <form class="form" action="none" id="kt_modal_upload_form">
                @csrf

                <div class="modal-header">
                    <h2 class="fw-bold">Upload files</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>

                <input type="hidden" name="order_id" value="{{ $order->id }}">
                
                <input type="file" name="file" id="file" class="d-none">


                <div class="modal-body pt-10 pb-15 px-lg-17">
                    <div class="form-group">
                        <div class="dropzone dropzone-queue mb-2" id="kt_modal_upload_dropzone">

                            <div class="dropzone-panel mb-4">
                                <a class="dropzone-select btn btn-sm btn-primary me-2 dz-clickable attach-file">Attach file</a>
                                <a class="dropzone-upload btn btn-sm btn-light-primary me-2">Upload All</a>
                                <a class="dropzone-remove-all btn btn-sm btn-light-primary">Remove All</a>
                            </div>

                            <div class="dropzone-items wm-200px">
                            </div>

                            <div class="dz-default dz-message"><button class="dz-button" type="button">Drop files here
                                    to upload</button></div>
                        </div>

                        <span class="form-text fs-6 text-muted">Max file size is 100MB per file.</span>

                    </div>
                </div>

            </form>

        </div>
    </div>
</div>


