        <!-- Mailing Address -->
        <div class="d-flex align-items-center justify-content-between mb-5">
            <h5>Mailing Address</h5>
            <label class="form-check form-switch mb-0">
              <input 
                id="mailingAddressToggle"
                name="fields[mailing_address_toggle]"
                value="1"
                type="checkbox" 
                class="form-check-input section-toggle" 
                data-section="mailing-address" 
                @if( $values['mailing_address_toggle'] == 1 ) checked @endif
                >
            </label>
          </div>
  
          <div class="toggle-section" id="section-mailing-address">
    
            <div class="row mb-6">
              <label class="col-lg-4 col-form-label fw-semibold fs-6">Address 1</label>
              <div class="col-lg-4 fv-row">
                <input 
                    type="text" 
                    class="form-control form-control-lg form-control-solid" 
                    placeholder="Address 1"
                    name="fields[mailing_address1]" 
                    value="{{ $values['mailing_address1'] ?? '' }}"
                    />
              </div>
            </div>
      
            <div class="row mb-6">
              <label class="col-lg-4 col-form-label fw-semibold fs-6">Address 2</label>
              <div class="col-lg-4 fv-row">
                <input 
                    type="text" 
                    class="form-control form-control-lg form-control-solid" 
                    placeholder="Address 2"
                    name="fields[mailing_address2]" 
                    value="{{ $values['mailing_address2'] ?? '' }}"
                    />
              </div>
            </div>
      
            <div class="row mb-6">
              <label class="col-lg-4 col-form-label fw-semibold fs-6">City</label>
              <div class="col-lg-4 fv-row">
                <input 
                    type="text" 
                    class="form-control form-control-lg form-control-solid" 
                    placeholder="City"
                    name="fields[mailing_address_city]" 
                    value="{{ $values['mailing_address_city'] ?? '' }}"
                    />
              </div>
            </div>
      
            <div class="row mb-6">
  
                <x-select 
                    inputName="fields[mailing_address_state_id]"
                    label="Select a State"
                    :options="$formRefs['country_state']['options']"
                    value="{{ $values['mailing_address_state_id'] ?? '' }}"
                    :multiple=false
                    :required=true
                    template="inline"
                />
  
            </div>
      
            <div class="row mb-6">
              <label class="col-lg-4 col-form-label fw-semibold fs-6">Zip Code</label>
              <div class="col-lg-4 fv-row">
                <input 
                    type="text" 
                    class="form-control form-control-lg form-control-solid" 
                    placeholder="Zip Code"
                    name="fields[mailing_address_zip]" 
                    value="{{ $values['mailing_address_zip'] ?? '' }}"
                    />
              </div>
            </div>
  
          </div> <!-- End Mailing Address Section -->