<h2 class="mb-6">General</h2>

<div class="row mb-6">
  <label class="col-lg-4 col-form-label fw-semibold fs-6">Name</label>
  <div class="col-lg-4 fv-row">
    <input 
        type="text" 
        class="form-control form-control-lg form-control-solid" 
        placeholder="Company Name"
        name="fields[company_name]" 
        value="{{ $values['company_name'] ?? '' }}"
        />
  </div>
</div>

<div class="row mb-6">
  <label class="col-lg-4 col-form-label fw-semibold fs-6">Usdot</label>
  <div class="col-lg-4 fv-row">
    <input 
        type="number" 
        class="form-control form-control-lg form-control-solid" 
        placeholder="Usdot"
        name="fields[usdot]" 
        value="{{ $values['usdot'] ?? '' }}"
        />
  </div>
</div>

<div class="separator mb-8"></div>
<h2 class="mb-6">Contact info</h2>

<div class="row mb-6">
  <label class="col-lg-4 col-form-label fw-semibold fs-6">Primary Contact Name</label>
  <div class="col-lg-4 fv-row">
    <input 
        type="text" 
        class="form-control form-control-lg form-control-solid" 
        placeholder="Name"
        name="fields[primary_contact_name]" 
        value="{{ $values['primary_contact_name'] ?? '' }}"
        />
  </div>
</div>

<div class="row mb-6">
  <label class="col-lg-4 col-form-label fw-semibold fs-6">Primary Contact Email</label>
  <div class="col-lg-4 fv-row">
    <input 
        type="email" 
        class="form-control form-control-lg form-control-solid" 
        placeholder="Email"
        name="fields[primary_contact_email]" 
        value="{{ $values['primary_contact_email'] ?? '' }}"
        />
  </div>
</div>

<div class="row mb-6">
  <label class="col-lg-4 col-form-label fw-semibold fs-6">Primary Contact Phone</label>
  <div class="col-lg-4 fv-row">
    <input 
        type="text" 
        class="form-control form-control-lg form-control-solid" 
        placeholder="Phone"
        name="fields[primary_contact_phone]" 
        value="{{ $values['primary_contact_phone'] ?? '' }}"
        />
  </div>
</div>