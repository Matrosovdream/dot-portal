
      <!-- PIN -->
      <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">PIN</label>
        <div class="col-lg-4 fv-row">
          <input 
            type="text" 
            name="pin" 
            class="form-control form-control-lg form-control-solid" 
            placeholder="PIN"
            name="fields[pin]"
            value="{{ $values['pin'] ?? '' }}"
            >
        </div>
      </div>
  
      <!-- Change Type -->
      <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">Change Type</label>
        <div class="col-lg-4 fv-row">

            @php
                $changeTypes = [
                    'keep' => 'Keep same',
                    'change' => 'Make changes'
                ];
            @endphp

          <select name="fields[change_type]" id="changeType" class="form-select form-select-lg form-select-solid">
            @foreach($changeTypes as $value => $label)
              <option 
                value="{{ $value }}" 
                {{ (isset($values['change_type']) && $values['change_type'] === $value) ? 'selected' : '' }}>
                {{ $label }}
              </option>
            @endforeach
          </select>
        </div>
      </div>
  
      <!-- Editable Fields -->
      <div id="editableFields" style="display: block;">

        <div class="separator mb-8"></div>

        <!-- Main Info -->
        <h5 class="mt-10 mb-5">Main Info</h5>
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

        <div class="separator mb-8"></div>
  
        <!-- Business Address -->
        <h5 class="mt-10 mb-5">Business Address</h5>
  
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
                name="business_address2" 
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
                name="business_address_city" 
                value="{{ $values['business_address_city'] ?? '' }}"
                />
          </div>
        </div>
  
        <div class="row mb-6">

            <x-select 
                inputName="fields[business_address_state]"
                label="Select a State"
                :options="[
                    ['value' => 'NY', 'title' => 'New York'],
                    ['value' => 'CA', 'title' => 'California'],
                    ['value' => 'TX', 'title' => 'Texas'],
                    ['value' => 'FL', 'title' => 'Florida'],
                ]"
                value="{{ $values['business_address_state'] ?? '' }}"
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
                name="business_address_zip" 
                value="{{ $values['business_address_zip'] ?? '' }}"
                />
          </div>
        </div>

        <div class="separator mb-8"></div>
  
        <!-- Mailing Address -->
        <h5 class="mt-10 mb-5">Mailing Address</h5>
  
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
                name="mailing_address2" 
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
                name="mailing_address_city" 
                value="{{ $values['mailing_address_city'] ?? '' }}"
                />
          </div>
        </div>
  
        <div class="row mb-6">

            <x-select 
                inputName="fields[mailing_address_state]"
                label="Select a State"
                :options="[
                    ['value' => 'NY', 'title' => 'New York'],
                    ['value' => 'CA', 'title' => 'California'],
                    ['value' => 'TX', 'title' => 'Texas'],
                    ['value' => 'FL', 'title' => 'Florida'],
                ]"
                value="{{ $values['mailing_address_state'] ?? '' }}"
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
                name="mailing_address_zip" 
                value="{{ $values['mailing_address_zip'] ?? '' }}"
                />
          </div>
        </div>

        <div class="separator mb-8"></div>
  
        <!-- Contact Info -->
        <h5 class="mt-10 mb-5">Contact Info</h5>
  
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

        <div class="separator mb-8"></div>
  
        <!-- Operation Info -->
        <h5 class="mt-10 mb-5">Operation Info</h5>
  
        <div class="row mb-6">
          
            <x-select 
                inputName="fields[operation_type]"
                label="Operation Type"
                :options="[
                    ['value' => 'interstate', 'title' => 'Interstate'],
                    ['value' => 'intrastate', 'title' => 'Intrastate'],
                ]"
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
                :options="[
                    ['value' => 'general', 'title' => 'General Freight'],
                    ['value' => 'hazardous', 'title' => 'Hazardous Materials'],
                    ['value' => 'household', 'title' => 'Household Goods'],
                    ['value' => 'passenger', 'title' => 'Passenger'],
                ]"
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
  
      </div> <!-- End Editable Fields -->
  
  <!-- JS: Toggle Editable Fields + File Preview -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const changeType = document.getElementById('changeType');
      const editableFields = document.getElementById('editableFields');
  
      changeType.addEventListener('change', function () {
        editableFields.style.display = this.value === 'change' ? 'block' : 'none';
      });
  
      dropzone.addEventListener('click', function (e) {
        if (!e.target.closest('button')) fileInput.click();
      });

    });
  </script>
  