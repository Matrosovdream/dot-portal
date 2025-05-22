<ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold">

    <li class="nav-item">
        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
            href="#kt_ecommerce_customer_fields">
            Fields
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
            href="#kt_ecommerce_customer_edit_fields">
            Edit Fields
        </a>
    </li>

    @if( $request['service']['is_paid'] )
        <li class="nav-item">
            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                href="#kt_ecommerce_customer_payments">
                Payments
            </a>
        </li>
    @endif

    <!--
    <li class="nav-item">
        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" 
            href="#kt_ecommerce_customer_history">
            History
        </a>
    </li>
    -->

</ul>