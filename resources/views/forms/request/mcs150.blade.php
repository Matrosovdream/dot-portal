
      <!-- PIN -->
      <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">PIN</label>
        <div class="col-lg-4 fv-row">
          <input type="text" name="pin" class="form-control form-control-lg form-control-solid" placeholder="PIN">
        </div>
      </div>
  
      <!-- Change Type -->
      <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6">Change Type</label>
        <div class="col-lg-4 fv-row">
          <select name="change_type" id="changeType" class="form-select form-select-lg form-select-solid">
            <option value="keep">Keep same</option>
            <option value="change">Make changes</option>
          </select>
        </div>
      </div>
  
      <!-- Editable Fields -->
      <div id="editableFields" style="display: none;">

        <div class="separator mb-8"></div>

        <!-- Main Info -->
        <h5 class="mt-10 mb-5">Main Info</h5>
                <!-- EIN -->
        <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-semibold fs-6">EIN</label>
            <div class="col-lg-4 fv-row">
                <input type="text" name="ein" class="form-control form-control-lg form-control-solid" placeholder="EIN">
            </div>
        </div>

        <div class="separator mb-8"></div>
  
        <!-- Business Address -->
        <h5 class="mt-10 mb-5">Business Address</h5>
  
        <div class="row mb-6">
          <label class="col-lg-4 col-form-label fw-semibold fs-6">Address 1</label>
          <div class="col-lg-4 fv-row">
            <input type="text" name="business_address1" class="form-control form-control-lg form-control-solid" placeholder="Address 1">
          </div>
        </div>
  
        <div class="row mb-6">
          <label class="col-lg-4 col-form-label fw-semibold fs-6">Address 2</label>
          <div class="col-lg-4 fv-row">
            <input type="text" name="business_address2" class="form-control form-control-lg form-control-solid" placeholder="Address 2">
          </div>
        </div>
  
        <div class="row mb-6">
          <label class="col-lg-4 col-form-label fw-semibold fs-6">City</label>
          <div class="col-lg-4 fv-row">
            <input type="text" name="business_city" class="form-control form-control-lg form-control-solid" placeholder="City">
          </div>
        </div>
  
        <div class="row mb-6">
          <label class="col-lg-4 col-form-label fw-semibold fs-6">State</label>
          <div class="col-lg-4 fv-row">
            <select name="business_state" class="form-select form-select-lg form-select-solid">
              <option value="">Select a State</option>
              <option value="NY">New York</option>
              <option value="CA">California</option>
            </select>
          </div>
        </div>
  
        <div class="row mb-6">
          <label class="col-lg-4 col-form-label fw-semibold fs-6">Zip Code</label>
          <div class="col-lg-4 fv-row">
            <input type="text" name="business_zip" class="form-control form-control-lg form-control-solid" placeholder="Zip Code">
          </div>
        </div>

        <div class="separator mb-8"></div>
  
        <!-- Mailing Address -->
        <h5 class="mt-10 mb-5">Mailing Address</h5>
  
        <div class="row mb-6">
          <label class="col-lg-4 col-form-label fw-semibold fs-6">Address 1</label>
          <div class="col-lg-4 fv-row">
            <input type="text" name="mailing_address1" class="form-control form-control-lg form-control-solid" placeholder="Address 1">
          </div>
        </div>
  
        <div class="row mb-6">
          <label class="col-lg-4 col-form-label fw-semibold fs-6">Address 2</label>
          <div class="col-lg-4 fv-row">
            <input type="text" name="mailing_address2" class="form-control form-control-lg form-control-solid" placeholder="Address 2">
          </div>
        </div>
  
        <div class="row mb-6">
          <label class="col-lg-4 col-form-label fw-semibold fs-6">City</label>
          <div class="col-lg-4 fv-row">
            <input type="text" name="mailing_city" class="form-control form-control-lg form-control-solid" placeholder="City">
          </div>
        </div>
  
        <div class="row mb-6">
          <label class="col-lg-4 col-form-label fw-semibold fs-6">State</label>
          <div class="col-lg-4 fv-row">
            <select name="mailing_state" class="form-select form-select-lg form-select-solid">
              <option value="">Select a State</option>
              <option value="TX">Texas</option>
              <option value="FL">Florida</option>
            </select>
          </div>
        </div>
  
        <div class="row mb-6">
          <label class="col-lg-4 col-form-label fw-semibold fs-6">Zip Code</label>
          <div class="col-lg-4 fv-row">
            <input type="text" name="mailing_zip" class="form-control form-control-lg form-control-solid" placeholder="Zip Code">
          </div>
        </div>

        <div class="separator mb-8"></div>
  
        <!-- Contact Info -->
        <h5 class="mt-10 mb-5">Contact Info</h5>
  
        <div class="row mb-6">
          <label class="col-lg-4 col-form-label fw-semibold fs-6">Name</label>
          <div class="col-lg-4 fv-row">
            <input type="text" name="contact_name" class="form-control form-control-lg form-control-solid" placeholder="Contact Name">
          </div>
        </div>
  
        <div class="row mb-6">
          <label class="col-lg-4 col-form-label fw-semibold fs-6">Phone</label>
          <div class="col-lg-4 fv-row">
            <input type="tel" name="contact_phone" class="form-control form-control-lg form-control-solid" placeholder="Phone Number">
          </div>
        </div>
  
        <div class="row mb-6">
          <label class="col-lg-4 col-form-label fw-semibold fs-6">Email</label>
          <div class="col-lg-4 fv-row">
            <input type="email" name="contact_email" class="form-control form-control-lg form-control-solid" placeholder="Email Address">
          </div>
        </div>

        <div class="separator mb-8"></div>
  
        <!-- Operation Info -->
        <h5 class="mt-10 mb-5">Operation Info</h5>
  
        <div class="row mb-6">
          <label class="col-lg-4 col-form-label fw-semibold fs-6">Operation Type</label>
          <div class="col-lg-4 fv-row">
            <select name="operation_type" class="form-select form-select-lg form-select-solid">
              <option value="">Select Type</option>
              <option value="interstate">Interstate</option>
              <option value="intrastate">Intrastate</option>
            </select>
          </div>
        </div>
  
        <div class="row mb-6">
          <label class="col-lg-4 col-form-label fw-semibold fs-6">Cargo Type</label>
          <div class="col-lg-4 fv-row">
            <input type="text" name="cargo_type" class="form-control form-control-lg form-control-solid" placeholder="Cargo Type">
          </div>
        </div>
  
        <div class="row mb-6">
          <label class="col-lg-4 col-form-label fw-semibold fs-6">Mileage</label>
          <div class="col-lg-4 fv-row">
            <input type="number" name="mileage" class="form-control form-control-lg form-control-solid" placeholder="Mileage">
          </div>
        </div>
  
      </div> <!-- End Editable Fields -->
  
  <!-- JS: Toggle Editable Fields + File Preview -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const changeType = document.getElementById('changeType');
      const editableFields = document.getElementById('editableFields');
      const fileInput = document.getElementById('profile_photo_file');
      const dropzone = document.getElementById('kt_ecommerce_add_profile_photo');
  
      changeType.addEventListener('change', function () {
        editableFields.style.display = this.value === 'change' ? 'block' : 'none';
      });
  
      dropzone.addEventListener('click', function (e) {
        if (!e.target.closest('button')) fileInput.click();
      });
  
      fileInput.addEventListener('change', function () {
        const file = fileInput.files[0];
        document.querySelectorAll('.file-preview').forEach(el => el.remove());
        if (!file) return;
  
        const previewContainer = document.createElement('div');
        previewContainer.className = 'file-preview mt-4';
  
        if (file.type.startsWith('image/')) {
          const img = document.createElement('img');
          img.src = URL.createObjectURL(file);
          img.style.maxWidth = '100%';
          img.onload = () => URL.revokeObjectURL(img.src);
          previewContainer.appendChild(img);
        }
  
        dropzone.appendChild(previewContainer);
      });
    });
  </script>
  