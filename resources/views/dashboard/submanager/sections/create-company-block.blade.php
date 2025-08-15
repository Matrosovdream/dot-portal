
<div class="card pt-4 mb-6 mb-xl-9">

    <div class="card-header border-0">
        <div class="card-title">
            <h2>Company</h2>
        </div>
    </div>

    <div class="card-body pt-0 pb-5">

        <div class="fv-row mb-7">
            <div class="fv-row mb-8 position-relative">
                <label class="fs-6 fw-semibold mb-2 required">Dot Number</label>
                <input 
                        type="text" 
                        name="company[dot_number]" 
                        id="usdot"
                        class="form-control mb-2 {{ $errors->has('company.dot_number') ? 'is-invalid' : '' }}"
                        placeholder="" 
                        value="{{ old('company.dot_number') }}" 
                        />
                <span id="usdot-loader" class="spinner-border text-primary position-absolute top-50 end-0 d-none" 
                    style="margin-top: 0px; margin-right: 9px;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </span>
            </div>
        </div>

        <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2 required">Company Name</label>
            <input 
                    type="text" 
                    name="company[name]" 
                    id="company_name"
                    class="form-control mb-2 {{ $errors->has('company.name') ? 'is-invalid' : '' }}"
                    placeholder="" 
                    value="{{ old('company.name') }}" 
                    />
        </div>

        <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2 required">MC Number</label>
            <input 
                    type="text" 
                    name="company[mc_number]" 
                    id="mc_number"
                    class="form-control mb-2 {{ $errors->has('company.mc_number') ? 'is-invalid' : '' }}"
                    placeholder="" 
                    value="{{ old('company.mc_number') }}" 
                    />
        </div>

        <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2 required">Phone</label>
            <input 
                    type="text" 
                    name="company[phone]" 
                    id="company_phone"
                    class="form-control mb-2 {{ $errors->has('company.phone') ? 'is-invalid' : '' }}"
                    placeholder="" 
                    value="{{ old('company.phone') }}" 
                    />
        </div>

    </div>
</div>



