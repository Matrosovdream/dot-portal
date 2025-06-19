<form action="" method="GET">

    <div class="card-header align-items-center py-5 gap-2 gap-md-5">

        <div class="card-title"></div>

        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">

            <div class="col-lg-4 fv-row w-150px">
                <input type="date" name="date_from" class="form-control form-control-lg datepicker"
                    placeholder="Report from" value="{{ request()->date_from ?? '' }}" />
            </div>

            <div class="col-lg-4 fv-row w-150px">
                <input type="date" name="date_to" class="form-control form-control-lg datepicker"
                    placeholder="Report to" value="{{ request()->date_to ?? '' }}" />
            </div>

            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" name="q" value="{{ request()->q ?? '' }}"
                    data-kt-ecommerce-product-filter="search" class="form-control form-control-solid w-250px ps-12"
                    placeholder="Find inspections">
            </div>

            <button type="submit" class="btn btn-primary">Filter</button>

        </div>
    </div>

</form>