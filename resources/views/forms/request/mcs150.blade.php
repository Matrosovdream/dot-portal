
      <!-- PIN -->
      <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6 required">PIN</label>
        <div class="col-lg-4 fv-row">
          <input 
            type="text" 
            class="form-control form-control-lg form-control-solid" 
            placeholder="PIN"
            name="fields[pin]"
            value="{{ $values['pin'] ?? '' }}"
            >
        </div>
      </div>

      <!-- Change Type -->
      <div class="row mb-6">

        <label class="col-lg-4 col-form-label fw-semibold fs-6 required">Change Type</label>
        <div class="col-lg-4 fv-row">
          <select name="fields[change_type]" id="changeType" class="form-select form-select-lg form-select-solid">
            <option value="keep" @if( $values['change_type'] == 'keep' ) selected @endif>Keep same</option>
            <option value="change" @if( $values['change_type'] == 'change' ) selected @endif>Make changes</option>
          </select>
        </div>

      </div>

      <!-- Editable Fields -->
      <div id="editableFields" style="display: none;">

        <div class="separator mb-8"></div>

        <!-- Main Info -->
        <div class="d-flex align-items-center justify-content-between mb-5">
          <h5>Main Info</h5>
          <label class="form-check form-switch mb-0">
            <input 
              name="fields[main_info_toggle]"
              type="checkbox" 
              class="form-check-input section-toggle" 
              data-section="main-info" 
              id="mainInfoToggle" 
              value="1"
              @if( $values['main_info_toggle'] == 1 ) checked @endif
              >
          </label>
        </div>

        <div class="toggle-section" id="section-main-info">
        
          <!-- EIN -->
          <div class="row mb-6">
              <label class="col-lg-4 col-form-label fw-semibold fs-6">EIN</label>
              <div class="col-lg-4 fv-row">
                  <input 
                      type="text" 
                      class="form-control form-control-lg form-control-solid" 
                      placeholder="EIN"
                      name="fields[ein]"
                      value="{{ $values['ein'] ?? '' }}"
                      />
              </div>
          </div>

        </div> <!-- End Main Info Section -->

        <div class="separator mb-8"></div>

                 <!-- Business address -->
                <div class="d-flex align-items-center justify-content-between mb-5">
                  <h5>Business address</h5>
                  <label class="form-check form-switch mb-0">
                    <input 
                      name="fields[business_address_toggle]"
                      type="checkbox" 
                      value="1"
                      class="form-check-input section-toggle" 
                      data-section="business-address" 
                      id="businessAddressToggle" 
                      @if( $values['business_address_toggle'] == 1 ) checked @endif
                      >
                  </label>
                </div>

                <div class="toggle-section" id="section-business-address">
    
                  <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Address 1</label>
                    <div class="col-lg-4 fv-row">
                      <input 
                          type="text" 
                          class="form-control form-control-lg form-control-solid" 
                          placeholder="Address 1"
                          name="fields[business_address1]" 
                          value="{{ $values['business_address1'] ?? '' }}"
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
                          name="fields[business_address2]" 
                          value="{{ $values['business_address2'] ?? '' }}"
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
                          name="fields[business_address_city]" 
                          value="{{ $values['business_address_city'] ?? '' }}"
                          />
                    </div>
                  </div>
            
                  <div class="row mb-6">
        
                      <x-select 
                          inputName="fields[business_address_state_id]"
                          label="Select a State"
                          :options="$formRefs['country_state']['options']"
                          value="{{ $values['business_address_state_id'] ?? '' }}"
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
                          name="fields[business_address_zip]" 
                          value="{{ $values['business_address_zip'] ?? '' }}"
                          />
                    </div>
                  </div>

                </div> <!-- End Business Address Section -->

        <div class="separator mb-8"></div>        
  
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

        <div class="separator mb-8"></div>
  
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
              @if( $values['contact_info_toggle'] == 1 ) checked @endif
              >
          </label>
        </div>

        <div class="toggle-section" id="section-contact-info">
  
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-semibold fs-6">Name</label>
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

        </div> <!-- End Contact Info Section -->

        <div class="separator mb-8"></div>
  
        <!-- Operation Info -->
        <div class="d-flex align-items-center justify-content-between">
          <h5 class="">Operation Info</h5>
          <label class="form-check form-switch mb-0">
            <input 
              id="operationInfoToggle"
              name="fields[operation_info_toggle]"
              value="1"
              type="checkbox" 
              class="form-check-input section-toggle" 
              data-section="operation-info" 
              @if( $values['operation_info_toggle'] == 1 ) checked @endif
              >
          </label>
        </div>
        
        <div class="toggle-section" id="section-operation-info">
  
          <div class="row mb-6">
            
              <x-select 
                  inputName="fields[operation_type]"
                  label="Operation Type"
                  :options="$formRefs['operation_type']['options']"
                  value="{{ $values['operation_type'] ?? '' }}"
                  :multiple=false
                  :required=true
                  template="inline"
              />
              
          </div>
    
          <div class="row mb-6">
            
              <x-select 
                  inputName="fields[cargo_type]"
                  label="Cargo Type"
                  :options="$formRefs['cargo_type']['options']"
                  value="{{ $values['cargo_type'] ?? '' }}"
                  :multiple=false
                  :required=true
                  template="inline"
              />
              
          </div>
    
          <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-semibold fs-6">Mileage</label>
            <div class="col-lg-4 fv-row">
              <input 
                  type="number" 
                  class="form-control form-control-lg form-control-solid" 
                  placeholder="Mileage"
                  name="fields[mileage]"
                  value="{{ $values['mileage'] ?? '' }}">
            </div>
          </div>

        </div>
  
      </div> <!-- End Editable Fields -->
  
  <!-- JS: Toggle Editable Fields + File Preview -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const changeType = document.getElementById('changeType');
      const editableFields = document.getElementById('editableFields');
  
      changeType.addEventListener('change', function () {
        checkEditable();
      });

      // Check on page load
      checkEditable();
      
      dropzone.addEventListener('click', function (e) {
        if (!e.target.closest('button')) fileInput.click();
      });

    });

    function checkEditable() {
      editableFields.style.display = changeType.value === 'change' ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.section-toggle').forEach(function (toggle) {
        const sectionId = 'section-' + toggle.dataset.section;
        const sectionDiv = document.getElementById(sectionId);

        const updateVisibility = () => {
          sectionDiv.style.display = toggle.checked ? 'block' : 'none';
        };

        toggle.addEventListener('change', updateVisibility);

        // Use initial checkbox state on load
        updateVisibility();
      });
    });

  </script>

  
  