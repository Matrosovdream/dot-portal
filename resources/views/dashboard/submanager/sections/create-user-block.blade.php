
<div class="card pt-4 mb-6 mb-xl-9">

    <div class="card-header border-0">
        <div class="card-title">
            <h2>User</h2>
        </div>
    </div>

    <div class="card-body pt-0 pb-5">

        <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2 required">First name</label>
            <input 
                    type="text" 
                    name="user[firstname]" 
                    class="form-control mb-2 {{ $errors->has('user.firstname') ? 'is-invalid' : '' }}"
                    placeholder="" 
                    value="{{ old('user.firstname') }}" 
                    />
        </div>

        <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2 required">Last name</label>
            <input 
                    type="text" 
                    name="user[lastname]" 
                    class="form-control mb-2 {{ $errors->has('user.lastname') ? 'is-invalid' : '' }}"
                    placeholder="" 
                    value="{{ old('user.lastname') }}" 
                    />
        </div>

        <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2 required">Email</label>
            <input 
                    type="text" 
                    name="user[email]" 
                    class="form-control mb-2 {{ $errors->has('user.email') ? 'is-invalid' : '' }}"
                    placeholder="" 
                    value="{{ old('user.email') }}" 
                    />

            <!-- Error message -->
            @if (
                $errors->has('user.email')
                )
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('user.email') }}</strong>
                </span>
            @endif
        </div>

        <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2 required">Phone</label>
            <input 
                    type="text" 
                    name="user[phone]" 
                    class="form-control mb-2 {{ $errors->has('user.phone') ? 'is-invalid' : '' }}"
                    placeholder="" 
                    value="{{ old('user.phone') }}" 
                    />
        </div>

    </div>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- JS Logic -->
<script>
</script>


