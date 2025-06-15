<div id="kt_header_search" class="header-search d-flex align-items-stretch"
     data-kt-search-keypress="true"
     data-kt-search-min-length="2"
     data-kt-search-enter="enter"
     data-kt-search-layout="menu"
     data-kt-menu-trigger="auto"
     data-kt-menu-overflow="false"
     data-kt-menu-permanent="true"
     data-kt-menu-placement="bottom-end">
    <!--begin::Search toggle-->
    <div class="d-flex align-items-center" data-kt-search-element="toggle" id="kt_header_search_toggle">
        <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px">
            <i class="ki-duotone ki-magnifier fs-2">
                <span class="path1"></span><span class="path2"></span>
            </i>
        </div>
    </div>
    <!--end::Search toggle-->

    <!--begin::Menu-->
    <div data-kt-search-element="content"
         class="menu menu-sub menu-sub-dropdown p-7"
         style="width: 50vw; max-width: 100%;">
        <!--begin::Wrapper-->
        <div data-kt-search-element="wrapper">
            <!--begin::Form-->
            <form data-kt-search-element="form" class="w-100 position-relative mb-3" autocomplete="off">
                <i class="ki-duotone ki-magnifier fs-2 text-gray-500 position-absolute top-50 translate-middle-y ms-0">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <input type="text" class="search-input form-control form-control-flush ps-10"
                       name="search" placeholder="Search..." data-kt-search-element="input" />
                <span class="search-spinner position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-1"
                      data-kt-search-element="spinner">
                    <span class="spinner-border h-15px w-15px align-middle text-gray-500"></span>
                </span>
                <span class="search-reset btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 d-none"
                      data-kt-search-element="clear">
                    <i class="ki-duotone ki-cross fs-2 fs-lg-1 me-0">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                </span>
            </form>
            <!--end::Form-->

            <!--begin::Separator-->
            <div class="separator border-gray-200 mb-6"></div>
            <!--end::Separator-->

            <!--begin::Tabs-->
            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6" id="searchTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="tab-documents" data-bs-toggle="tab" href="#search-tab-documents" role="tab">
                        Documents <span id="documentsCount" class="text-muted">(0)</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="tab-drivers" data-bs-toggle="tab" href="#search-tab-drivers" role="tab">
                        Drivers <span id="driversCount" class="text-muted">(0)</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="tab-vehicles" data-bs-toggle="tab" href="#search-tab-vehicles" role="tab">
                        Vehicles <span id="vehiclesCount" class="text-muted">(0)</span>
                    </a>
                </li>
            </ul>
            
            <!--end::Tabs-->

            <!--begin::Tab content-->
            <div class="tab-content" id="searchTabsContent" data-kt-search-element="results">

                <div class="tab-pane fade show active" id="search-tab-documents" role="tabpanel">
                    <div id="documentsResults" class="scroll-y pe-3" style="max-height: 300px;"></div>
                    <div id="documentsShowAll" class="text-center mt-4"></div>
                </div>

                <div class="tab-pane fade" id="search-tab-drivers" role="tabpanel">
                    <div id="driversResults" class="scroll-y pe-3" style="max-height: 300px;"></div>
                    <div id="driversShowAll" class="text-center mt-4"></div>
                </div>


                <div class="tab-pane fade" id="search-tab-vehicles" role="tabpanel">
                    <div id="vehiclesResults" class="scroll-y pe-3" style="max-height: 300px;"></div>
                    <div id="vehiclesShowAll" class="text-center mt-4"></div>
                </div>

            </div>
            <!--end::Tab content-->
        </div>
        <!--end::Wrapper-->

    </div>
    <!--end::Menu-->
</div>



<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const searchInput = document.querySelector('[data-kt-search-element="input"]');
    const spinner = document.querySelector('[data-kt-search-element="spinner"]');

    searchInput.addEventListener('input', function () {
        const query = this.value.trim();
        if (query.length < 2) {
            resetAllTabs();
            return;
        }

        spinner.classList.remove('d-none');

        axios.post('/dashboard/search', { q: query })
            .then(response => {
                const data = response.data;

                const isEmpty = (
                    !data.documents?.items?.length &&
                    !data.drivers?.items?.length &&
                    !data.vehicles?.items?.length
                );

                if (isEmpty) {
                    resetAllTabs();
                    return;
                }

                updateTab(data.documents, 'documentsResults', 'documentsShowAll', 'documentsCount');
                updateTab(data.drivers, 'driversResults', 'driversShowAll', 'driversCount', 'drivers');
                updateTab(data.vehicles, 'vehiclesResults', 'vehiclesShowAll', 'vehiclesCount', 'vehicles');
            })
            .catch(error => {
                console.error('Search failed:', error);
                resetAllTabs();
            })
            .finally(() => {
                spinner.classList.add('d-none');
            });
    });

    function resetAllTabs() {
        updateTab({ items: [], count: 0 }, 'documentsResults', 'documentsShowAll', 'documentsCount');
        updateTab({ items: [], count: 0 }, 'driversResults', 'driversShowAll', 'driversCount');
        updateTab({ items: [], count: 0 }, 'vehiclesResults', 'vehiclesShowAll', 'vehiclesCount');
    }

    function updateTab(section, containerId, showAllId, countId, type = 'default') {
        const container = document.getElementById(containerId);
        const showAll = document.getElementById(showAllId);
        const countEl = document.getElementById(countId);

        container.innerHTML = '';
        showAll.innerHTML = '';

        const count = section?.count ?? 0;
        const items = section?.items ?? [];

        if (countEl) {
            countEl.innerText = `(${count})`;
        }

        if (!items.length) {
            container.innerHTML = '<div class="text-muted">No results found.</div>';
            return;
        }

        items.forEach(item => {
            let element = '';

            if (type === 'drivers') {
                const user = item.user ?? {};
                const fullname = `${user.firstname ?? ''} ${user.lastname ?? ''}`.trim() || 'Unnamed';
                const dob = user.birthday ?? 'N/A';
                const ssn = item.ssn ?? 'N/A';
                const hireDate = item.hire_date ?? 'N/A';
                const url = '/dashboard/my-drivers/' + item.id;

                element = `
                    <div class="mb-6">
                        <a href="${url}" class="fs-5 fw-bold text-hover-primary d-block mb-1">${fullname}</a>
                        <div class="d-flex flex-wrap text-muted fs-8 gap-4 mt-2">
                            <span><i class="bi bi-calendar2-week"></i> DOB: ${dob}</span>
                            <span><i class="bi bi-credit-card-2-front"></i> SSN: ${ssn}</span>
                            <span><i class="bi bi-briefcase"></i> Hire Date: ${hireDate}</span>
                        </div>
                    </div>
                `;
            } else if (type === 'vehicles') {
                const vin = item.vin ?? 'N/A';
                const number = item.number ?? 'N/A';
                const url = '/dashboard/vehicles/' + item.id;

                element = `
                    <div class="mb-6">
                        <a href="${url}" class="fs-5 fw-bold text-hover-primary d-block mb-1">
                            VIN: ${vin}
                        </a>
                        <div class="text-muted fs-8 mt-1">
                            <i class="bi bi-hash"></i> Number: ${number}
                        </div>
                    </div>
                `;
            } else {
                let title = item.title ?? item.filename ?? 'Untitled';
                let description = item.description ?? item.desription ?? 'No description available.';
                let url = item.showUrl ?? '#';
                let tags = item.tags?.items?.map(t => t.name).join(', ') ?? '';
                let meta = item.user?.company?.name ?? item.user?.fullname ?? 'Unknown';

                element = `
                    <div class="mb-6">
                        <a href="${url}" class="fs-5 fw-bold text-hover-primary d-block mb-1">${title}</a>
                        <div class="text-muted fs-7 mb-2">${description}</div>
                        <div class="d-flex flex-wrap text-muted fs-8 gap-3">
                            <span><i class="bi bi-person"></i> ${meta}</span>
                            ${tags ? `<span><i class="bi bi-tags"></i> ${tags}</span>` : ''}
                            ${item.type ? `<span><i class="bi bi-file-earmark"></i> ${item.type.split('/').pop().toUpperCase()}</span>` : ''}
                        </div>
                    </div>
                `;
            }

            container.insertAdjacentHTML('beforeend', element);
        });

        if (count > 15) {
            showAll.innerHTML = `
                <a href="${section.url}" class="btn btn-sm btn-light-primary fw-semibold">
                    Show All
                </a>
            `;
        }
    }
</script>
