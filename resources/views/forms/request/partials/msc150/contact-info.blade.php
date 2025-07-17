<!-- Contact Info -->
<div class="d-flex align-items-center justify-content-between mb-5">
    <h5 class="">Contact Info</h5>
    <label class="form-check form-switch mb-0">
      <input 
        id="contactInfoToggle"
        name="fields[contact_info_toggle]"
        value="1"
        type="checkbox" 
        class="form-check-input section-toggle" 
        data-section="contact-info" 
        @if( 
          isset($values['contact_info_toggle']) &&
          $values['contact_info_toggle'] == 1 
          ) checked @endif
        >
    </label>
  </div>

  <div class="toggle-section" id="section-contact-info">

    <div class="row mb-6">
      <label class="col-lg-4 col-form-label fw-semibold fs-6">Company Legal Name</label>
      <div class="col-lg-4 fv-row">
        <input 
            type="text" 
            class="form-control form-control-lg form-control-solid" 
            placeholder="Company Legal Name"
            name="fields[company_name]" 
            value="{{ $values['company_name'] ?? '' }}"
            />
      </div>
    </div>

    <div class="row mb-6">
      <label class="col-lg-4 col-form-label fw-semibold fs-6">Owner’s Name</label>
      <div class="col-lg-4 fv-row">
        <input 
            type="text" 
            class="form-control form-control-lg form-control-solid" 
            placeholder="Contact Name"
            name="fields[contact_name]" 
            value="{{ $values['contact_name'] ?? '' }}"
            />
      </div>
    </div>

    <div class="row mb-6">
      <label class="col-lg-4 col-form-label fw-semibold fs-6">Phone</label>
      <div class="col-lg-4 fv-row">
        <input 
            type="tel" 
            class="form-control form-control-lg form-control-solid" 
            placeholder="Phone Number"
            name="fields[contact_phone]" 
            value="{{ $values['contact_phone'] ?? '' }}"
            />
      </div>
    </div>

    <div class="row mb-6">
      <label class="col-lg-4 col-form-label fw-semibold fs-6">Email</label>
      <div class="col-lg-4 fv-row">
        <input 
            type="email" 
            class="form-control form-control-lg form-control-solid" 
            placeholder="Email Address"
            name="fields[contact_email]" 
            value="{{ $values['contact_email'] ?? '' }}">
      </div>
    </div>

  </div> 
  <!-- End Contact Info Section -->