<div class="row mb-6">

    <label class="col-lg-4 col-form-label fw-semibold fs-6 required" for="fields[cargo_type]">
      Hazardous Materials
    </label>
      
    <table class="table table-bordered">
      <thead>
          <tr>
              <th>Title</th>
              <th>Carrier</th>
              <th>Shipper</th>
              <th>Bulk</th>
              <th>Non-Bulk</th>
          </tr>
      </thead>
      <tbody>
          @foreach($formRefs['hazardous_materials']['options'] as $item)
              <tr>
                    <td>
                        {{ $item['title'] }}
                    </td>
                    <td class="clickable-cell text-center">
                        <input 
                            type="checkbox" 
                            class="form-check-input form-check-input-lg" 
                            name="fields[hazardous_materials][{{ $item['value'] }}_carrier]" 
                            @if( isset( $values[ 'hazardous_materials'][$item['value'].'_carrier'] ) ) checked @endif
                            value="1"
                            >
                    </td>
                    <td class="clickable-cell text-center">
                        <input 
                            type="checkbox" 
                            class="form-check-input form-check-input-lg" 
                            name="fields[hazardous_materials][{{ $item['value'] }}_shipper]"
                            @if( isset( $values[ 'hazardous_materials'][$item['value'].'_shipper'] ) ) checked @endif
                            value="1"
                            >
                    </td>
                    <td class="clickable-cell text-center">
                        <input 
                            type="checkbox" 
                            class="form-check-input form-check-input-lg" 
                            name="fields[hazardous_materials][{{ $item['value'] }}_bulk]"
                            @if( isset( $values[ 'hazardous_materials'][$item['value'].'_bulk'] ) ) checked @endif
                            value="1"
                            >
                    </td>
                    <td class="clickable-cell text-center">
                        <input 
                            type="checkbox" 
                            class="form-check-input form-check-input-lg" 
                            name="fields[hazardous_materials][{{ $item['value'] }}_non_bulk]"
                            @if( isset( $values[ 'hazardous_materials'][$item['value'].'_non_bulk'] ) ) checked @endif
                            value="1"
                            >
                    </td>
              </tr>
          @endforeach
      </tbody>
    </table>
  
    
  </div>