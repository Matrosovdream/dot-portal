<div class="modal fade" id="kt_modal_driverhistory_record_{{ $item['id'] }}" tabindex="-1" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit driver history record #{{ $item['id'] }}</h2>
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
                    action="{{ route('dashboard.vehicles.show.driverhistory.update', ['vehicle_id' => $vehicle['id'], 'drh_id' => $item['id']]) }}"
                    enctype="multipart/form-data"
                    >
                    @csrf
                    
                    @include('dashboard.includes.errors.default')

                    <div class="row mb-5">

                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                            <label class="form-label required">Start date</label>
                            <input 
                                type="date" 
                                name="start_date" 
                                class="form-control form-control-solid mb-2 datepicker" 
                                value="{{ $item['start_date'] }}"
                                />
                        </div>

                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                            <label class="form-label required">End date</label>
                            <input 
                                type="date" 
                                name="end_date" 
                                class="form-control form-control-solid mb-2 datepicker" 
                                value="{{ $item['end_date'] }}"
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

                                @foreach($references['driver']['items'] as $driver)
                                    <option 
                                        value="{{ $driver['id'] }}" 
                                        {{ $driver['id'] == $item['driver_id'] ? 'selected' : '' }}
                                        >
                                        {{ implode(' ', [$driver['firstname'], $driver['lastname']]) }}
                                        
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