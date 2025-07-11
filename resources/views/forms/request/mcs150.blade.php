      <!-- Dot -->
      <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-semibold fs-6 required">Dot Number</label>
        <div class="col-lg-4 fv-row">
          <input 
            type="text" 
            class="form-control form-control-lg form-control-solid" 
            placeholder="Dot Number"
            name="fields[dot_number]"
            value="{{ $values['dot_number'] ?? '' }}"
            >
        </div>
      </div>

      
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
        @include('forms.request.partials.msc150.main-info')
        <div class="separator mb-8"></div>

        <!-- Contact Info -->
        @include('forms.request.partials.msc150.contact-info')
        <div class="separator mb-8"></div>

        <!-- Business Address -->
        @include('forms.request.partials.msc150.business-address')
        <div class="separator mb-8"></div>        
  
        <!-- Mailing Address -->
        @include('forms.request.partials.msc150.mailing-address')
        <div class="separator mb-8"></div>
  
        <!-- Operation Info -->
        @include('forms.request.partials.msc150.operation-info')
        <div class="separator mb-8"></div>

        <!-- Driver counts -->
        @include('forms.request.partials.msc150.driver-counts')
  
      </div> 
      
      <!-- End Editable Fields -->
  
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

  
<style>
  .clickable-cell {
      cursor: pointer;
      user-select: none;
  }

  .clickable-cell input[type="checkbox"] {
      pointer-events: none; /* allow click to bubble to td */
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('td.clickable-cell').forEach(td => {
          td.addEventListener('click', function () {
              const checkbox = this.querySelector('input[type="checkbox"]');
              if (checkbox) checkbox.checked = !checkbox.checked;
          });
      });
  });
</script>
