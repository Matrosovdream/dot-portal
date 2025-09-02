<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Clearinghouse Management</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #0f172a0a; }
    .sidebar { min-width: 240px; }
    .nav-link .exclaim { font-size: .65rem; top: 0; right: 0.35rem; }
    .badge-dot { width:.5rem;height:.5rem;display:inline-block;border-radius:50%; }
    .badge-enrolled { background:#16a34a; }
    .badge-not { background:#ef4444; }
    .table thead th { white-space: nowrap; }
    .status-badge { font-size:.8rem; }
    .sticky-actions { position: sticky; right: 0; background: #fff; }
    .card-blurred { backdrop-filter: blur(6px); }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- LEFT NAV -->
    <aside class="sidebar border-end bg-white">
      <div class="p-3 border-bottom">
        <span class="fs-5 fw-bold">Dot Portal</span>
      </div>
      <ul class="nav flex-column p-2">
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center gap-2" href="#">
            <i class="bi bi-speedometer"></i> Dashboard
          </a>
        </li>
        <li class="nav-item position-relative">
          <a id="navDrivers" class="nav-link d-flex align-items-center gap-2 position-relative" href="#drivers">
            <i class="bi bi-people"></i> Drivers
            <span id="navDriversExclaim" class="position-absolute exclaim translate-middle-y badge rounded-pill bg-danger d-none" style="right:8px;">!</span>
          </a>
        </li>
        <li class="nav-item position-relative">
          <a id="navClearinghouse" class="nav-link active d-flex align-items-center gap-2 position-relative" href="#">
            <i class="bi bi-shield-check"></i> Clearinghouse
            <span id="navClearinghouseExclaim" class="position-absolute exclaim translate-middle-y badge rounded-pill bg-danger d-none" style="right:8px;">!</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center gap-2" href="#">
            <i class="bi bi-ui-checks-grid"></i> Compliance
          </a>
        </li>
      </ul>
    </aside>

    <!-- MAIN -->
    <main class="flex-grow-1">
      <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h1 class="h3 mb-0">Clearinghouse Management</h1>
          <div class="text-muted">Manage FMCSA Clearinghouse enrollment, queries, and testing.</div>
        </div>

        <!-- ATTENTION BANNER (auto-shown by JS if any CDL not enrolled) -->
        <div id="bannerCdlNotEnrolled" class="alert alert-warning d-none" role="alert">
          <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>
              <strong>You have CDL drivers. Register in Clearinghouse.</strong>
              <a href="#enrollment" class="alert-link ms-2">Go to Enrollment / Roster</a>
            </div>
          </div>
        </div>

        <!-- HEADER SUMMARY CARD -->
        <section id="enrollment" class="mb-4">
          <div class="card shadow-sm">
            <div class="card-body d-flex flex-wrap gap-4 align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-3">
                <i class="bi bi-buildings fs-2 text-primary"></i>
                <div>
                  <div class="text-muted small">Company</div>
                  <div class="fs-5 fw-semibold" id="companyName">Acme Logistics LLC</div>
                </div>
              </div>

              <div class="vr d-none d-md-block"></div>

              <div class="d-flex align-items-center gap-3">
                <i class="bi bi-shield-lock fs-2 text-success"></i>
                <div>
                  <div class="text-muted small">Clearinghouse Enrollment</div>
                  <div class="d-flex align-items-center gap-2">
                    <span id="companyEnrollmentBadge" class="badge rounded-pill text-bg-secondary">Not Registered</span>
                    <button id="btnRegisterCompany" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalRegisterCompany">
                      Register Company
                    </button>
                  </div>
                </div>
              </div>

              <div class="vr d-none d-md-block"></div>

              <div class="d-flex align-items-center gap-3">
                <i class="bi bi-search fs-2 text-info"></i>
                <div>
                  <div class="text-muted small">Query Balance</div>
                  <div class="d-flex align-items-center gap-2">
                    <span class="fs-5 fw-semibold" id="queryBalance">0</span>
                    <span class="text-muted">remaining</span>
                    <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalBuyQueries">
                      Buy Queries
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- TO-DOs -->
        <section class="mb-4">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
              <div class="d-flex align-items-center gap-2">
                <i class="bi bi-list-check"></i>
                <span class="fw-semibold">To‑Do</span>
              </div>
            </div>
            <div class="card-body">
              <ul id="todoList" class="list-group list-group-flush small"></ul>
              <div id="todoEmpty" class="text-muted small d-none">All set—no pending actions.</div>
            </div>
          </div>
        </section>

        <!-- DRIVER ROSTER TABLE -->
        <section id="drivers" class="mb-5">
          <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center gap-2">
                <i class="bi bi-people"></i>
                <span class="fw-semibold">Driver Roster</span>
              </div>
              <div class="d-flex gap-2">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEnrollRandomPoolMulti"><i class="bi bi-shuffle me-1"></i> Enroll in Random Pool (Bulk)</button>
                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalRunQueriesBulk"><i class="bi bi-lightning me-1"></i> Run Queries (Bulk)</button>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Driver</th>
                    <th>CDL</th>
                    <th>Clearinghouse</th>
                    <th>Consent Status</th>
                    <th>Last Query</th>
                    <th>Random Testing Pool</th>
                    <th class="text-end">Actions</th>
                  </tr>
                </thead>
                <tbody id="driverTableBody"></tbody>
              </table>
            </div>
          </div>
        </section>

      </div>
    </main>
  </div>

  <!-- MODALS -->
  <div class="modal fade" id="modalRegisterCompany" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-shield-lock me-2"></i>Register Company in Clearinghouse</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="mb-0 small text-muted">Link your USDOT and FMCSA portal credentials to complete registration.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button id="confirmRegisterCompany" type="button" class="btn btn-primary">Register</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalBuyQueries" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-search me-2"></i>Buy Queries</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label class="form-label">Quantity</label>
          <input id="buyQueriesQty" type="number" class="form-control" min="1" step="1" value="5" />
          <div class="form-text">Limited and full queries both decrement your balance.</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button id="confirmBuyQueries" type="button" class="btn btn-primary">Add to Balance</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Per‑Driver Modals (one shared, populated by JS) -->
  <div class="modal fade" id="modalEnrollCH" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-shield-plus me-2"></i>Enroll Driver in Clearinghouse</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="small text-muted">Driver</div>
          <div id="modalEnrollCHDriver" class="fw-semibold"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button id="confirmEnrollCH" type="button" class="btn btn-primary">Enroll</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalSendConsent" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-envelope-check me-2"></i>Send Consent Request</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="small text-muted">Driver</div>
          <div id="modalSendConsentDriver" class="fw-semibold"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button id="confirmSendConsent" type="button" class="btn btn-primary">Send</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalRunQuery" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-lightning me-2"></i>Run Clearinghouse Query</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="small text-muted">Driver</div>
          <div id="modalRunQueryDriver" class="fw-semibold mb-2"></div>
          <label class="form-label">Query Type</label>
          <select id="queryType" class="form-select">
            <option value="limited">Limited</option>
            <option value="full">Full</option>
          </select>
          <div class="form-text">Running a query will decrement your balance by 1.</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button id="confirmRunQuery" type="button" class="btn btn-primary">Run Query</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalScheduleTest" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-clipboard2-pulse me-2"></i>Schedule Drug/Alcohol Test</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="small text-muted">Driver</div>
          <div id="modalScheduleTestDriver" class="fw-semibold mb-2"></div>
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Test Type</label>
              <select id="testType" class="form-select">
                <option value="drug">Drug</option>
                <option value="alcohol">Alcohol</option>
                <option value="drug_alcohol">Drug + Alcohol</option>
                <option value="return_to_duty">Return‑to‑Duty</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Preferred Date</label>
              <input id="testDate" type="date" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Preferred Time</label>
              <input id="testTime" type="time" class="form-control">
            </div>
            <div class="col-12">
              <label class="form-label">Notes</label>
              <textarea id="testNotes" class="form-control" rows="2" placeholder="Instructions for the clinic or the driver"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button id="confirmScheduleTest" type="button" class="btn btn-primary">Schedule</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalEnrollRandomPool" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-shuffle me-2"></i>Enroll in Random Testing Pool</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="small text-muted">Driver</div>
          <div id="modalEnrollRandomDriver" class="fw-semibold"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button id="confirmEnrollRandom" type="button" class="btn btn-primary">Enroll</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bulk helper modals -->
  <div class="modal fade" id="modalRunQueriesBulk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-lightning me-2"></i>Run Queries (Bulk)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label class="form-label">Query Type</label>
          <select id="bulkQueryType" class="form-select">
            <option value="limited">Limited</option>
            <option value="full">Full</option>
          </select>
          <div class="form-text">Runs for all CDL drivers in roster.</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button id="confirmRunQueriesBulk" type="button" class="btn btn-primary">Run</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalEnrollRandomPoolMulti" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-shuffle me-2"></i>Enroll CDL Drivers into Random Pool</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="small text-muted mb-0">All CDL drivers will be enrolled.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button id="confirmEnrollRandomBulk" type="button" class="btn btn-primary">Enroll All CDL</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Toasts -->
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
    <div id="appToast" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body" id="toastBody">Done</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // --- Sample data (replace with API bindings) ---
    const state = {
      company: {
        name: 'Acme Logistics LLC',
        clearinghouseRegistered: false,
        queryBalance: 2,
      },
      drivers: [
        {
          id: 1,
          name: 'John Carter',
          cdl: true,
          clearinghouseEnrolled: false,
          consent: 'not_given', // pending | not_given | accepted | passed | violation
          lastQuery: { type: 'limited', result: 'N/A' }, // Clear | Violation | N/A
          randomPool: false,
        },
        {
          id: 2,
          name: 'Alexis Rivera',
          cdl: true,
          clearinghouseEnrolled: true,
          consent: 'accepted',
          lastQuery: { type: 'full', result: 'Clear' },
          randomPool: true,
        },
        {
          id: 3,
          name: 'Samir Patel',
          cdl: false,
          clearinghouseEnrolled: false,
          consent: 'not_given',
          lastQuery: { type: 'limited', result: 'N/A' },
          randomPool: false,
        },
        {
          id: 4,
          name: 'Taylor Nguyen',
          cdl: true,
          clearinghouseEnrolled: true,
          consent: 'pending',
          lastQuery: { type: 'limited', result: 'N/A' },
          randomPool: false,
        },
      ],
    };

    const consentMap = {
      pending: { label: 'Pending', cls: 'text-bg-warning' },
      not_given: { label: 'Consent Not Given', cls: 'text-bg-secondary' },
      accepted: { label: 'Accepted / Completed', cls: 'text-bg-primary' },
      passed: { label: 'Passed / No Record', cls: 'text-bg-success' },
      violation: { label: 'Violation Found', cls: 'text-bg-danger' },
    };

    function showToast(msg) {
      document.getElementById('toastBody').textContent = msg;
      const toast = new bootstrap.Toast(document.getElementById('appToast'));
      toast.show();
    }

    function renderSummary() {
      document.getElementById('companyName').textContent = state.company.name;
      document.getElementById('queryBalance').textContent = state.company.queryBalance;

      const badge = document.getElementById('companyEnrollmentBadge');
      if (state.company.clearinghouseRegistered) {
        badge.className = 'badge rounded-pill text-bg-success';
        badge.textContent = 'Registered';
      } else {
        badge.className = 'badge rounded-pill text-bg-secondary';
        badge.textContent = 'Not Registered';
      }
    }

    function makeBadge(text, cls) {
      const span = document.createElement('span');
      span.className = `badge status-badge ${cls}`;
      span.textContent = text;
      return span;
    }

    function renderDrivers() {
      const tbody = document.getElementById('driverTableBody');
      tbody.innerHTML = '';

      state.drivers.forEach(d => {
        const tr = document.createElement('tr');

        // Driver (with badges under name)
        const tdDriver = document.createElement('td');
        tdDriver.innerHTML = `<div class="fw-semibold">${d.name}</div>`;
        const sub = document.createElement('div');
        sub.className = 'small mt-1 d-flex gap-2';
        if (d.clearinghouseEnrolled) sub.append(makeBadge('Clearinghouse Enrolled', 'text-bg-success'));
        if (d.randomPool) sub.append(makeBadge('Random Pool', 'text-bg-info'));
        if (!d.clearinghouseEnrolled && d.cdl) sub.append(makeBadge('Not Enrolled', 'text-bg-danger'));
        tdDriver.append(sub);

        // CDL
        const tdCdl = document.createElement('td');
        tdCdl.append(makeBadge(d.cdl ? 'Yes' : 'No', d.cdl ? 'text-bg-primary' : 'text-bg-secondary'));

        // Clearinghouse (Yes/No)
        const tdCH = document.createElement('td');
        tdCH.append(makeBadge(d.clearinghouseEnrolled ? 'Yes' : 'No', d.clearinghouseEnrolled ? 'text-bg-success' : 'text-bg-secondary'));

        // Consent Status
        const tdConsent = document.createElement('td');
        const c = consentMap[d.consent] ?? consentMap.not_given;
        tdConsent.append(makeBadge(c.label, c.cls));

        // Last Query
        const tdLast = document.createElement('td');
        const lastType = d.lastQuery?.type ? (d.lastQuery.type === 'full' ? 'Full' : 'Limited') : '—';
        const lastResult = d.lastQuery?.result ?? '—';
        tdLast.innerHTML = `<div class="d-flex flex-column">
          <span class="small text-muted">${lastType}</span>
          <span class="fw-semibold">${lastResult}</span>
        </div>`;

        // Random Pool
        const tdPool = document.createElement('td');
        tdPool.append(makeBadge(d.randomPool ? 'Yes' : 'No', d.randomPool ? 'text-bg-info' : 'text-bg-secondary'));

        // Actions
        const tdActions = document.createElement('td');
        tdActions.className = 'text-end';
        tdActions.innerHTML = `
          <div class="btn-group" role="group">
            <button class="btn btn-outline-primary btn-sm" data-action="enroll-ch" data-id="${d.id}" ${d.clearinghouseEnrolled ? 'disabled' : ''}>Enroll in CH</button>
            <button class="btn btn-outline-secondary btn-sm" data-action="send-consent" data-id="${d.id}">Send Consent</button>
            <button class="btn btn-outline-success btn-sm" data-action="run-limited" data-id="${d.id}">Run Limited</button>
            <button class="btn btn-outline-success btn-sm" data-action="run-full" data-id="${d.id}">Run Full</button>
            <button class="btn btn-outline-warning btn-sm" data-action="schedule-test" data-id="${d.id}">Schedule Test</button>
            <button class="btn btn-outline-info btn-sm" data-action="enroll-random" data-id="${d.id}" ${d.randomPool ? 'disabled' : ''}>Enroll in Random</button>
          </div>`;

        tr.append(tdDriver, tdCdl, tdCH, tdConsent, tdLast, tdPool, tdActions);
        tbody.append(tr);
      });

      // Wire row action buttons
      tbody.querySelectorAll('button[data-action]').forEach(btn => {
        btn.addEventListener('click', handleRowAction);
      });
    }

    function computeTodosAndBadges() {
      const list = document.getElementById('todoList');
      const empty = document.getElementById('todoEmpty');
      list.innerHTML = '';

      const cdlDrivers = state.drivers.filter(d => d.cdl);
      const cdlNotEnrolled = cdlDrivers.filter(d => !d.clearinghouseEnrolled);
      const cdlNotRandom = cdlDrivers.filter(d => !d.randomPool);
      const hasPendingConsent = state.drivers.some(d => d.consent === 'pending');
      const hasViolation = state.drivers.some(d => d.consent === 'violation' || d.lastQuery?.result === 'Violation');

      // Banner
      document.getElementById('bannerCdlNotEnrolled').classList.toggle('d-none', cdlNotEnrolled.length === 0);

      // To‑Dos
      const addItem = (text, icon, cls='') => {
        const li = document.createElement('li');
        li.className = `list-group-item d-flex align-items-center gap-2 ${cls}`;
        li.innerHTML = `<i class="bi ${icon}"></i><span>${text}</span>`;
        list.append(li);
      };

      if (cdlNotEnrolled.length > 0) {
        addItem('Enroll CDL driver(s) in Clearinghouse.', 'bi-shield-exclamation');
      }
      if (cdlNotRandom.length > 0) {
        addItem('Enroll driver(s) in Random Testing Pool.', 'bi-shuffle');
      }
      if (hasPendingConsent) {
        addItem('Follow up on pending consent(s).', 'bi-envelope-exclamation');
      }
      if (state.company.queryBalance <= 0) {
        addItem('Buy more query checks.', 'bi-search');
      }
      if (hasViolation) {
        addItem('Violation found — review and take action.', 'bi-exclamation-octagon', 'text-danger');
      }

      empty.classList.toggle('d-none', list.children.length > 0);

      // Left‑nav exclamation badges
      const flagIssues = (cdlNotEnrolled.length > 0) || (cdlNotRandom.length > 0) || hasPendingConsent || hasViolation;
      document.getElementById('navDriversExclaim').classList.toggle('d-none', !flagIssues);
      document.getElementById('navClearinghouseExclaim').classList.toggle('d-none', !flagIssues);
    }

    // --- Row actions ---
    let currentDriverId = null;

    function handleRowAction(evt) {
      const btn = evt.currentTarget;
      const id = +btn.dataset.id;
      const action = btn.dataset.action;
      currentDriverId = id;
      const driver = state.drivers.find(d => d.id === id);

      if (!driver) return;

      if (action === 'enroll-ch') {
        document.getElementById('modalEnrollCHDriver').textContent = driver.name;
        new bootstrap.Modal('#modalEnrollCH').show();
      }
      if (action === 'send-consent') {
        document.getElementById('modalSendConsentDriver').textContent = driver.name;
        new bootstrap.Modal('#modalSendConsent').show();
      }
      if (action === 'run-limited' || action === 'run-full') {
        document.getElementById('modalRunQueryDriver').textContent = driver.name;
        document.getElementById('queryType').value = action === 'run-full' ? 'full' : 'limited';
        new bootstrap.Modal('#modalRunQuery').show();
      }
      if (action === 'schedule-test') {
        document.getElementById('modalScheduleTestDriver').textContent = driver.name;
        new bootstrap.Modal('#modalScheduleTest').show();
      }
      if (action === 'enroll-random') {
        document.getElementById('modalEnrollRandomDriver').textContent = driver.name;
        new bootstrap.Modal('#modalEnrollRandomPool').show();
      }
    }

    // --- Confirm buttons ---
    document.getElementById('confirmRegisterCompany').addEventListener('click', () => {
      state.company.clearinghouseRegistered = true;
      renderSummary();
      computeTodosAndBadges();
      showToast('Company registered in Clearinghouse.');
      bootstrap.Modal.getInstance(document.getElementById('modalRegisterCompany')).hide();
    });

    document.getElementById('confirmBuyQueries').addEventListener('click', () => {
      const qty = Math.max(1, parseInt(document.getElementById('buyQueriesQty').value || '0', 10));
      state.company.queryBalance += qty;
      renderSummary();
      computeTodosAndBadges();
      showToast(`Added ${qty} quer${qty === 1 ? 'y' : 'ies'} to balance.`);
      bootstrap.Modal.getInstance(document.getElementById('modalBuyQueries')).hide();
    });

    document.getElementById('confirmEnrollCH').addEventListener('click', () => {
      const d = state.drivers.find(x => x.id === currentDriverId);
      if (d) {
        d.clearinghouseEnrolled = true;
        renderDrivers();
        computeTodosAndBadges();
        showToast(`${d.name} enrolled in Clearinghouse.`);
      }
      bootstrap.Modal.getInstance(document.getElementById('modalEnrollCH')).hide();
    });

    document.getElementById('confirmSendConsent').addEventListener('click', () => {
      const d = state.drivers.find(x => x.id === currentDriverId);
      if (d) {
        d.consent = 'pending';
        renderDrivers();
        computeTodosAndBadges();
        showToast(`Consent request sent to ${d.name}.`);
      }
      bootstrap.Modal.getInstance(document.getElementById('modalSendConsent')).hide();
    });

    document.getElementById('confirmRunQuery').addEventListener('click', () => {
      const type = document.getElementById('queryType').value; // limited | full
      if (state.company.queryBalance <= 0) {
        showToast('Insufficient query balance. Please buy more.');
        return;
      }
      const d = state.drivers.find(x => x.id === currentDriverId);
      if (d) {
        state.company.queryBalance -= 1;
        // Simple demo logic: if consent not accepted and full query, mark pending
        if (type === 'full' && d.consent !== 'accepted') {
          d.consent = 'pending';
        }
        // Simulate result: if violation status flagged, return Violation else Clear
        const result = (d.consent === 'violation') ? 'Violation' : (Math.random() < 0.06 ? 'Violation' : 'Clear');
        d.lastQuery = { type, result };
        renderSummary();
        renderDrivers();
        computeTodosAndBadges();
        showToast(`${type === 'full' ? 'Full' : 'Limited'} query run for ${d.name}.`);
      }
      bootstrap.Modal.getInstance(document.getElementById('modalRunQuery')).hide();
    });

    document.getElementById('confirmScheduleTest').addEventListener('click', () => {
      const d = state.drivers.find(x => x.id === currentDriverId);
      const date = document.getElementById('testDate').value;
      const time = document.getElementById('testTime').value;
      if (d) {
        showToast(`Test scheduled for ${d.name} on ${date || 'TBD'} ${time || ''}`.trim());
      }
      bootstrap.Modal.getInstance(document.getElementById('modalScheduleTest')).hide();
    });

    document.getElementById('confirmEnrollRandom').addEventListener('click', () => {
      const d = state.drivers.find(x => x.id === currentDriverId);
      if (d) {
        d.randomPool = true;
        renderDrivers();
        computeTodosAndBadges();
        showToast(`${d.name} enrolled in Random Testing Pool.`);
      }
      bootstrap.Modal.getInstance(document.getElementById('modalEnrollRandomPool')).hide();
    });

    document.getElementById('confirmRunQueriesBulk').addEventListener('click', () => {
      const type = document.getElementById('bulkQueryType').value;
      const targets = state.drivers.filter(d => d.cdl);
      const needed = targets.length;
      if (state.company.queryBalance < needed) {
        showToast(`Need ${needed} queries but only ${state.company.queryBalance} available.`);
        return;
      }
      state.company.queryBalance -= needed;
      targets.forEach(d => {
        d.lastQuery = { type, result: 'Clear' };
      });
      renderSummary();
      renderDrivers();
      computeTodosAndBadges();
      showToast(`Ran ${type} queries for ${targets.length} CDL drivers.`);
      bootstrap.Modal.getInstance(document.getElementById('modalRunQueriesBulk')).hide();
    });

    document.getElementById('confirmEnrollRandomBulk').addEventListener('click', () => {
      state.drivers.forEach(d => { if (d.cdl) d.randomPool = true; });
      renderDrivers();
      computeTodosAndBadges();
      showToast('All CDL drivers enrolled in Random Testing Pool.');
      bootstrap.Modal.getInstance(document.getElementById('modalEnrollRandomPoolMulti')).hide();
    });

    // Init
    renderSummary();
    renderDrivers();
    computeTodosAndBadges();
  </script>
</body>
</html>
