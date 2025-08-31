

<div id="tab_registration">

  <h2 class="mb-6">General</h2>
  
  <div class="row mb-6">
    <x-select 
      inputName="fields[drivers]"
      label="Choose Drivers"
      inputId="drivers"
      :options="$formRefs['drivers']['options']"
      value="{{ $values['drivers'] ?? '' }}"
      :multiple=true
      :required=true
      template="inline"
    />
  </div>

  <div class="row mb-6">
    <x-select 
      inputName="fields[test_type]"
      label="Test type"
      inputId="test-type"
      :options="$formRefs['test_type']['options']"
      value="{{ $values['test_type'] ?? '' }}"
      :multiple=false
      :required=true
      template="inline"
    />
  </div>
  

</div>  



<script>
  (function () {
    // Ensure jQuery exists (Select2 requires it anyway)
    if (typeof window.jQuery === 'undefined') return;
    const $ = window.jQuery;
  
    const $price = $('#service-price');
    const $drivers = $('#drivers');
    const $testType = $('#test-type'); // kept for future modifiers
  
    function getBasePrice() {
      const d = $price.data('price');
      if (d !== undefined && d !== null && d !== '') return parseFloat(String(d).replace(/,/g, '')) || 0;
  
      const txt = ($price.text() || '').replace(/[^\d.,-]/g, '').replace(/,/g, '');
      return parseFloat(txt) || 0;
    }
  
    function driversCount() {
      // For Select2 multiple, .val() is an array (or null)
      const v = $drivers.val();
      if (Array.isArray(v)) return v.filter(Boolean).length;
      return v ? 1 : 0; // single-select fallback
    }
  
    function formatCurrency(n) {
      try {
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(n);
      } catch {
        return '$' + (Math.round(n * 100) / 100).toFixed(2);
      }
    }
  
    function recalc() {
      const base = getBasePrice(); // price per driver
      const count = driversCount();
      const total = base * count;  // use Math.max(count,1) if you want min=1
      $price.text(formatCurrency(total));
    }
  
    // Recalc on any way the value can change
    $(document)
      .on('change', '#drivers', recalc)
      .on('select2:select select2:unselect select2:clear', '#drivers', recalc);
  
    // Hooks for test type if you later add price modifiers
    $(document)
      .on('change', '#test-type', recalc)
      .on('select2:select select2:unselect select2:clear', '#test-type', recalc);
  
    // Initial run after page loads (and after Select2 possibly initializes)
    $(function () {
      // Run immediately and once more on next tick to catch late init/defaults
      recalc();
      setTimeout(recalc, 0);
    });
  })();
  </script>
  