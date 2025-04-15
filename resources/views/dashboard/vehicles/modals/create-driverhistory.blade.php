<div class="modal fade" id="kt_modal_driverhistory_new" tabindex="-1" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add new driver history record</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>

            <div class="modal-body py-lg-10 px-lg-10">
                <form 
                    method="POST"
                    action="{{ route('dashboard.vehicles.show.driverhistory.store', ['vehicle_id' => $vehicle['id']]) }}"
                    enctype="multipart/form-data"
                    >
                    @csrf

                    <input type="hidden" name="driver_id" value="1" />

                    @include('dashboard.includes.errors.default')

                    <div class="row mb-5">

                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                            <label class="form-label required">Start date</label>
                            <input 
                                type="date" 
                                name="start_date" 
                                class="form-control form-control-solid mb-2" 
                                value="{{ old('start_date') }}"
                                />
                        </div>

                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                            <label class="form-label required">End date</label>
                            <input 
                                type="date" 
                                name="end_date" 
                                class="form-control form-control-solid mb-2" 
                                value="{{ old('end_date') }}"
                                />
                        </div>

                    </div>

                    <div class="row mb-5">

                        <div class="col-lg-4 fv-row">

                            <label class="form-label required">
                                Select driver
                            </label>

                            <select name="driver_id" aria-label="Select driver" data-control="select2"
                                data-placeholder="Select driver"
                                class="form-select form-select-solid form-select-lg fw-semibold">
                                <option value="">Select driver</option>

                                @foreach($references['driver']['items'] as $item)
                                    <option 
                                        value="{{ $item['id'] }}" 
                                        {{ $item['id'] == $vehicle['driver']['id'] ? 'selected' : '' }}
                                        >
                                        {{ implode(' ', [$item['firstname'], $item['lastname']]) }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                    </div> 

                    <div class="d-flex flex-stack">

                        <div></div>

                        <div>
                            <button type="submit" class="btn btn-lg btn-primary">
                                Save
                                <i class="ki-duotone ki-arrow-right fs-3 ms-1 me-0">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i></button>
                        </div>

                    </div>

                </form>

            </div>
        </div>

    </div>
</div>