<h2 class="mb-6">General</h2>


      <!-- Change Type -->
      <div class="row mb-6">

        @php /*
        <x-select 
              inputName="fields[change_type]"
              label="Change Type"
              :options="$formRefs['change_type']['options']"
              value="{{ $values['change_type'] ?? '' }}"
              :multiple=false
              :required=true
              template="inline"
        />
        */ @endphp

        <label class="col-lg-4 col-form-label fw-semibold fs-6">Change Type</label>
        <div class="col-lg-4 fv-row">
          <select name="fields[change_type]" id="changeType" class="form-select form-select-lg form-select-solid">
            <option value="new" @if( $values['change_type'] == 'new' ) selected @endif>New</option>
            <option value="renewal" @if( $values['change_type'] == 'renewal' ) selected @endif>Renewal</option>
          </select>
        </div>

      </div>

      <div class="row mb-6">
          <x-select 
              inputName="fields[vehicle_id]"
              label="Choose Vehicle"
              :options="$formRefs['vehicles']['options']"
              value="{{ $values['vehicle_id'] ?? '' }}"
              :multiple=false
              :required=true
              template="inline"
          />
      </div>

      <div class="separator mb-8"></div>

      <h2 class="mb-6">Address</h2>
  
      <!-- Editable Fields -->
      <div id="editableFields" style="display: none;">

        <div class="row mb-6">
          <x-select 
              inputName="fields[country_state_id]"
              label="State"
              :options="$formRefs['country_state']['options']"
              value="{{ $values['country_state_id'] ?? '' }}"
              :multiple=false
              :required=true
              template="inline"
          />
        </div>
  
      </div> 
      <!-- End Editable Fields -->
  
  <!-- JS: Toggle Editable Fields -->
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
      editableFields.style.display = changeType.value === 'new' ? 'block' : 'none';
    }
  </script>