<div class="page" id="page-dashboard" style="display:block;">

      <!-- Page Header -->
      <div class="page-header">
        <div>
          <h1 class="page-title">Command Center</h1>
          <p class="page-desc">Live overview · <span id="liveDate"></span></p>
        </div>
        <div class="header-actions">
          <button class="btn-ghost"><i class="bi bi-sliders2"></i> Filters</button>
          <div class="date-range-picker">
            <i class="bi bi-calendar3"></i>
            <select onchange="showToast('info','Date range updated','bi-calendar3')">
              <option>This Month</option>
              <option>Last 7 Days</option>
              <option>Last Quarter</option>
              <option>This Year</option>
            </select>
          </div>
          <button class="btn-ghost"><i class="bi bi-download"></i> Export</button>
          <button class="btn-primary-solid" onclick="openModal('quickAddModal')">
            <i class="bi bi-plus-lg"></i> Quick Add
          </button>
        </div>
      </div>

      <!-- KPI STRIP -->
      <div class="kpi-grid">
        <div class="kpi-card" style="--kpi-accent:#6366f1">
          <div class="kpi-top">
            <div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#6366f1"><i class="bi bi-currency-rupee"></i></div>
            <span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 18.4%</span>
          </div>
          <div class="kpi-value">₹48.6L</div>
          <div class="kpi-label">Total Revenue</div>
          <div class="kpi-spark">
            <div class="spark-bar" style="height:40%"></div><div class="spark-bar" style="height:60%"></div><div class="spark-bar" style="height:45%"></div><div class="spark-bar" style="height:75%"></div><div class="spark-bar" style="height:55%"></div><div class="spark-bar" style="height:90%"></div><div class="spark-bar active" style="height:100%"></div>
          </div>
        </div>
        <div class="kpi-card" style="--kpi-accent:#10b981">
          <div class="kpi-top">
            <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i class="bi bi-bag-check-fill"></i></div>
            <span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 12.1%</span>
          </div>
          <div class="kpi-value">238</div>
          <div class="kpi-label">Orders Closed</div>
          <div class="kpi-spark">
            <div class="spark-bar" style="height:30%;--kpi-accent:#10b981"></div><div class="spark-bar" style="height:50%;--kpi-accent:#10b981"></div><div class="spark-bar" style="height:65%;--kpi-accent:#10b981"></div><div class="spark-bar" style="height:45%;--kpi-accent:#10b981"></div><div class="spark-bar" style="height:80%;--kpi-accent:#10b981"></div><div class="spark-bar" style="height:60%;--kpi-accent:#10b981"></div><div class="spark-bar active" style="height:90%;--kpi-accent:#10b981"></div>
          </div>
        </div>
        <div class="kpi-card" style="--kpi-accent:#f59e0b">
          <div class="kpi-top">
            <div class="kpi-icon" style="background:rgba(245,158,11,.15);color:#f59e0b"><i class="bi bi-person-lines-fill"></i></div>
            <span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 9.8%</span>
          </div>
          <div class="kpi-value">1,247</div>
          <div class="kpi-label">Total Leads</div>
          <div class="kpi-spark">
            <div class="spark-bar" style="height:55%;--kpi-accent:#f59e0b"></div><div class="spark-bar" style="height:70%;--kpi-accent:#f59e0b"></div><div class="spark-bar" style="height:50%;--kpi-accent:#f59e0b"></div><div class="spark-bar" style="height:85%;--kpi-accent:#f59e0b"></div><div class="spark-bar" style="height:60%;--kpi-accent:#f59e0b"></div><div class="spark-bar" style="height:75%;--kpi-accent:#f59e0b"></div><div class="spark-bar active" style="height:95%;--kpi-accent:#f59e0b"></div>
          </div>
        </div>
        <div class="kpi-card" style="--kpi-accent:#8b5cf6">
          <div class="kpi-top">
            <div class="kpi-icon" style="background:rgba(139,92,246,.15);color:#8b5cf6"><i class="bi bi-kanban-fill"></i></div>
            <span class="kpi-trend down"><i class="bi bi-arrow-down-right"></i> 2.3%</span>
          </div>
          <div class="kpi-value">24</div>
          <div class="kpi-label">Active Projects</div>
          <div class="kpi-spark">
            <div class="spark-bar" style="height:80%;--kpi-accent:#8b5cf6"></div><div class="spark-bar" style="height:65%;--kpi-accent:#8b5cf6"></div><div class="spark-bar" style="height:90%;--kpi-accent:#8b5cf6"></div><div class="spark-bar" style="height:70%;--kpi-accent:#8b5cf6"></div><div class="spark-bar" style="height:55%;--kpi-accent:#8b5cf6"></div><div class="spark-bar" style="height:85%;--kpi-accent:#8b5cf6"></div><div class="spark-bar active" style="height:75%;--kpi-accent:#8b5cf6"></div>
          </div>
        </div>
        <div class="kpi-card" style="--kpi-accent:#ef4444">
          <div class="kpi-top">
            <div class="kpi-icon" style="background:rgba(239,68,68,.15);color:#ef4444"><i class="bi bi-people-fill"></i></div>
            <span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 4.2%</span>
          </div>
          <div class="kpi-value">52</div>
          <div class="kpi-label">Team Members</div>
          <div class="kpi-spark">
            <div class="spark-bar" style="height:50%;--kpi-accent:#ef4444"></div><div class="spark-bar" style="height:55%;--kpi-accent:#ef4444"></div><div class="spark-bar" style="height:60%;--kpi-accent:#ef4444"></div><div class="spark-bar" style="height:60%;--kpi-accent:#ef4444"></div><div class="spark-bar" style="height:65%;--kpi-accent:#ef4444"></div><div class="spark-bar" style="height:70%;--kpi-accent:#ef4444"></div><div class="spark-bar active" style="height:75%;--kpi-accent:#ef4444"></div>
          </div>
        </div>
        <div class="kpi-card" style="--kpi-accent:#06b6d4">
          <div class="kpi-top">
            <div class="kpi-icon" style="background:rgba(6,182,212,.15);color:#06b6d4"><i class="bi bi-clock-fill"></i></div>
            <span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 6.7%</span>
          </div>
          <div class="kpi-value">94.2%</div>
          <div class="kpi-label">Attendance Rate</div>
          <div class="kpi-spark">
            <div class="spark-bar" style="height:88%;--kpi-accent:#06b6d4"></div><div class="spark-bar" style="height:91%;--kpi-accent:#06b6d4"></div><div class="spark-bar" style="height:89%;--kpi-accent:#06b6d4"></div><div class="spark-bar" style="height:93%;--kpi-accent:#06b6d4"></div><div class="spark-bar" style="height:90%;--kpi-accent:#06b6d4"></div><div class="spark-bar" style="height:95%;--kpi-accent:#06b6d4"></div><div class="spark-bar active" style="height:94%;--kpi-accent:#06b6d4"></div>
          </div>
        </div>
      </div>

      <!-- MAIN GRID -->
      <div class="dash-grid">

        <!-- Revenue Chart -->
        <div class="dash-card span-8">
          <div class="card-head">
            <div>
              <div class="card-title">Revenue vs Target</div>
              <div class="card-sub">Monthly comparison · FY 2024–25</div>
            </div>
            <div class="card-actions">
              <div class="seg-control">
                <button class="seg-btn active">Monthly</button>
                <button class="seg-btn">Quarterly</button>
                <button class="seg-btn">Yearly</button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-legend">
              <span class="legend-item"><span class="l-dot" style="background:#6366f1"></span>Revenue</span>
              <span class="legend-item"><span class="l-dot" style="background:#10b981"></span>Target</span>
              <span class="legend-item"><span class="l-dot" style="background:#f59e0b"></span>Expenses</span>
            </div>
            <div class="bar-chart-wrap">
              <div class="y-axis">
                <span>50L</span><span>40L</span><span>30L</span><span>20L</span><span>10L</span><span>0</span>
              </div>
              <div class="bar-chart">
                <div class="bar-group" data-month="Apr">
                  <div class="bar-stack">
                    <div class="bar rev" style="height:62%" data-val="₹31L"></div>
                    <div class="bar tgt" style="height:70%" data-val="₹35L"></div>
                    <div class="bar exp" style="height:38%" data-val="₹19L"></div>
                  </div>
                  <div class="bar-label">Apr</div>
                </div>
                <div class="bar-group" data-month="May">
                  <div class="bar-stack">
                    <div class="bar rev" style="height:75%" data-val="₹37.5L"></div>
                    <div class="bar tgt" style="height:70%" data-val="₹35L"></div>
                    <div class="bar exp" style="height:42%" data-val="₹21L"></div>
                  </div>
                  <div class="bar-label">May</div>
                </div>
                <div class="bar-group" data-month="Jun">
                  <div class="bar-stack">
                    <div class="bar rev" style="height:58%" data-val="₹29L"></div>
                    <div class="bar tgt" style="height:72%" data-val="₹36L"></div>
                    <div class="bar exp" style="height:35%" data-val="₹17.5L"></div>
                  </div>
                  <div class="bar-label">Jun</div>
                </div>
                <div class="bar-group" data-month="Jul">
                  <div class="bar-stack">
                    <div class="bar rev" style="height:80%" data-val="₹40L"></div>
                    <div class="bar tgt" style="height:74%" data-val="₹37L"></div>
                    <div class="bar exp" style="height:45%" data-val="₹22.5L"></div>
                  </div>
                  <div class="bar-label">Jul</div>
                </div>
                <div class="bar-group" data-month="Aug">
                  <div class="bar-stack">
                    <div class="bar rev" style="height:88%" data-val="₹44L"></div>
                    <div class="bar tgt" style="height:76%" data-val="₹38L"></div>
                    <div class="bar exp" style="height:50%" data-val="₹25L"></div>
                  </div>
                  <div class="bar-label">Aug</div>
                </div>
                <div class="bar-group" data-month="Sep">
                  <div class="bar-stack">
                    <div class="bar rev" style="height:72%" data-val="₹36L"></div>
                    <div class="bar tgt" style="height:78%" data-val="₹39L"></div>
                    <div class="bar exp" style="height:40%" data-val="₹20L"></div>
                  </div>
                  <div class="bar-label">Sep</div>
                </div>
                <div class="bar-group" data-month="Oct">
                  <div class="bar-stack">
                    <div class="bar rev" style="height:95%" data-val="₹47.5L"></div>
                    <div class="bar tgt" style="height:80%" data-val="₹40L"></div>
                    <div class="bar exp" style="height:52%" data-val="₹26L"></div>
                  </div>
                  <div class="bar-label">Oct</div>
                </div>
                <div class="bar-group active" data-month="Nov">
                  <div class="bar-stack">
                    <div class="bar rev" style="height:97%;background:linear-gradient(180deg,#818cf8,#6366f1)" data-val="₹48.6L"></div>
                    <div class="bar tgt" style="height:82%" data-val="₹41L"></div>
                    <div class="bar exp" style="height:48%" data-val="₹24L"></div>
                  </div>
                  <div class="bar-label">Nov ●</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Project Pipeline Donut -->
        <div class="dash-card span-4">
          <div class="card-head">
            <div class="card-title">Project Pipeline</div>
            <button class="icon-btn-sm"><i class="bi bi-three-dots"></i></button>
          </div>
          <div class="card-body">
            <div class="donut-wrap">
              <svg viewBox="0 0 120 120" class="donut-svg">
                <circle cx="60" cy="60" r="50" fill="none" stroke="var(--b2)" stroke-width="18"/>
                <circle cx="60" cy="60" r="50" fill="none" stroke="#6366f1" stroke-width="18" stroke-dasharray="94.2 220.8" stroke-dashoffset="0" stroke-linecap="round"/>
                <circle cx="60" cy="60" r="50" fill="none" stroke="#10b981" stroke-width="18" stroke-dasharray="66 248.9" stroke-dashoffset="-94.2" stroke-linecap="round"/>
                <circle cx="60" cy="60" r="50" fill="none" stroke="#f59e0b" stroke-width="18" stroke-dasharray="44 271" stroke-dashoffset="-160.2" stroke-linecap="round"/>
                <circle cx="60" cy="60" r="50" fill="none" stroke="#ef4444" stroke-width="18" stroke-dasharray="30 285" stroke-dashoffset="-204.2" stroke-linecap="round"/>
                <text x="60" y="56" text-anchor="middle" fill="var(--t1)" font-size="14" font-weight="700" font-family="Plus Jakarta Sans">24</text>
                <text x="60" y="68" text-anchor="middle" fill="var(--t3)" font-size="6" font-family="Plus Jakarta Sans">Projects</text>
              </svg>
            </div>
            <div class="donut-legend">
              <div class="dl-item"><span class="dl-dot" style="background:#6366f1"></span><span class="dl-label">In Progress</span><span class="dl-val">10</span></div>
              <div class="dl-item"><span class="dl-dot" style="background:#10b981"></span><span class="dl-label">Completed</span><span class="dl-val">7</span></div>
              <div class="dl-item"><span class="dl-dot" style="background:#f59e0b"></span><span class="dl-label">Review</span><span class="dl-val">5</span></div>
              <div class="dl-item"><span class="dl-dot" style="background:#ef4444"></span><span class="dl-label">Delayed</span><span class="dl-val">2</span></div>
            </div>
          </div>
        </div>

        <!-- Active Projects Table -->
        <div class="dash-card span-8">
          <div class="card-head">
            <div>
              <div class="card-title">Active Projects</div>
              <div class="card-sub">Showing 5 of 24 active</div>
            </div>
            <div class="card-actions">
              <div class="filter-bar">
                <select class="filter-select"><option>All Status</option><option>In Progress</option><option>Review</option><option>Delayed</option></select>
                <select class="filter-select"><option>All Teams</option><option>Frontend</option><option>Backend</option><option>Mobile</option></select>
                <div class="search-mini"><i class="bi bi-search"></i><input type="text" placeholder="Search…"></div>
              </div>
              <button class="btn-primary-solid sm" onclick="openModal('addProjectModal')"><i class="bi bi-plus-lg"></i> New</button>
            </div>
          </div>
          <div class="table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th><input type="checkbox" class="cb-custom"></th>
                  <th>Project</th>
                  <th>Client</th>
                  <th>Team Lead</th>
                  <th>Stack</th>
                  <th>Progress</th>
                  <th>Deadline</th>
                  <th>Status</th>
                  <th>Budget</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><input type="checkbox" class="cb-custom"></td>
                  <td><div class="proj-cell"><div class="proj-icon" style="background:rgba(99,102,241,.15);color:#6366f1">OR</div><div><div class="proj-name">Orion E-Commerce</div><div class="proj-id">#PRJ-001</div></div></div></td>
                  <td><div class="client-cell">TechCorp Pvt Ltd</div></td>
                  <td><div class="member-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">AK</div>Arjun Kumar</div></td>
                  <td><div class="tag-list"><span class="tech-tag react">React</span><span class="tech-tag node">Node</span></div></td>
                  <td><div class="progress-cell"><div class="prog-bar-wrap"><div class="prog-fill" style="width:78%;background:#6366f1"></div></div><span class="prog-pct">78%</span></div></td>
                  <td><span class="date-cell warn">Dec 15</span></td>
                  <td><span class="status-pill inprog">In Progress</span></td>
                  <td><span class="money-cell">₹8.5L</span></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('projectDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-pencil-fill"></i></button><button class="ra-btn danger"><i class="bi bi-trash-fill"></i></button></div></td>
                </tr>
                <tr>
                  <td><input type="checkbox" class="cb-custom"></td>
                  <td><div class="proj-cell"><div class="proj-icon" style="background:rgba(16,185,129,.15);color:#10b981">FM</div><div><div class="proj-name">FinanceMe App</div><div class="proj-id">#PRJ-002</div></div></div></td>
                  <td><div class="client-cell">Nexus Finance</div></td>
                  <td><div class="member-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#10b981,#06b6d4)">PS</div>Priya Sharma</div></td>
                  <td><div class="tag-list"><span class="tech-tag flutter">Flutter</span><span class="tech-tag python">Python</span></div></td>
                  <td><div class="progress-cell"><div class="prog-bar-wrap"><div class="prog-fill" style="width:94%;background:#10b981"></div></div><span class="prog-pct">94%</span></div></td>
                  <td><span class="date-cell ok">Nov 30</span></td>
                  <td><span class="status-pill review">In Review</span></td>
                  <td><span class="money-cell">₹12.2L</span></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('projectDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-pencil-fill"></i></button><button class="ra-btn danger"><i class="bi bi-trash-fill"></i></button></div></td>
                </tr>
                <tr>
                  <td><input type="checkbox" class="cb-custom"></td>
                  <td><div class="proj-cell"><div class="proj-icon" style="background:rgba(239,68,68,.15);color:#ef4444">HR</div><div><div class="proj-name">HR Portal</div><div class="proj-id">#PRJ-003</div></div></div></td>
                  <td><div class="client-cell">InternalOps</div></td>
                  <td><div class="member-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#f59e0b,#ef4444)">RV</div>Ravi Verma</div></td>
                  <td><div class="tag-list"><span class="tech-tag vue">Vue</span><span class="tech-tag laravel">Laravel</span></div></td>
                  <td><div class="progress-cell"><div class="prog-bar-wrap"><div class="prog-fill" style="width:42%;background:#ef4444"></div></div><span class="prog-pct">42%</span></div></td>
                  <td><span class="date-cell danger">Nov 20</span></td>
                  <td><span class="status-pill delayed">Delayed</span></td>
                  <td><span class="money-cell">₹4.8L</span></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('projectDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-pencil-fill"></i></button><button class="ra-btn danger"><i class="bi bi-trash-fill"></i></button></div></td>
                </tr>
                <tr>
                  <td><input type="checkbox" class="cb-custom"></td>
                  <td><div class="proj-cell"><div class="proj-icon" style="background:rgba(6,182,212,.15);color:#06b6d4">SC</div><div><div class="proj-name">SmartCart PWA</div><div class="proj-id">#PRJ-004</div></div></div></td>
                  <td><div class="client-cell">RetailMax India</div></td>
                  <td><div class="member-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#06b6d4,#6366f1)">NK</div>Neha Kapoor</div></td>
                  <td><div class="tag-list"><span class="tech-tag next">Next.js</span><span class="tech-tag mongo">MongoDB</span></div></td>
                  <td><div class="progress-cell"><div class="prog-bar-wrap"><div class="prog-fill" style="width:61%;background:#06b6d4"></div></div><span class="prog-pct">61%</span></div></td>
                  <td><span class="date-cell ok">Jan 10</span></td>
                  <td><span class="status-pill inprog">In Progress</span></td>
                  <td><span class="money-cell">₹6.9L</span></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('projectDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-pencil-fill"></i></button><button class="ra-btn danger"><i class="bi bi-trash-fill"></i></button></div></td>
                </tr>
                <tr>
                  <td><input type="checkbox" class="cb-custom"></td>
                  <td><div class="proj-cell"><div class="proj-icon" style="background:rgba(245,158,11,.15);color:#f59e0b">AI</div><div><div class="proj-name">AI Analytics Suite</div><div class="proj-id">#PRJ-005</div></div></div></td>
                  <td><div class="client-cell">DataFirst Corp</div></td>
                  <td><div class="member-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)">SM</div>Sunita Mehta</div></td>
                  <td><div class="tag-list"><span class="tech-tag python">Python</span><span class="tech-tag react">React</span></div></td>
                  <td><div class="progress-cell"><div class="prog-bar-wrap"><div class="prog-fill" style="width:28%;background:#f59e0b"></div></div><span class="prog-pct">28%</span></div></td>
                  <td><span class="date-cell ok">Feb 28</span></td>
                  <td><span class="status-pill planning">Planning</span></td>
                  <td><span class="money-cell">₹18.0L</span></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('projectDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-pencil-fill"></i></button><button class="ra-btn danger"><i class="bi bi-trash-fill"></i></button></div></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="table-footer">
            <span class="tf-info">Showing 5 of 24 projects</span>
            <div class="tf-pagination">
              <button class="pg-btn"><i class="bi bi-chevron-double-left"></i></button>
              <button class="pg-btn"><i class="bi bi-chevron-left"></i></button>
              <button class="pg-btn active">1</button>
              <button class="pg-btn">2</button>
              <button class="pg-btn">3</button>
              <span class="pg-ellipsis">…</span>
              <button class="pg-btn">5</button>
              <button class="pg-btn"><i class="bi bi-chevron-right"></i></button>
              <button class="pg-btn"><i class="bi bi-chevron-double-right"></i></button>
            </div>
            <div class="tf-per-page">
              <span>Rows:</span>
              <select><option>5</option><option>10</option><option>25</option></select>
            </div>
          </div>
        </div>

        <!-- Attendance Today + Sales Pipeline -->
        <div class="dash-card span-4">
          <div class="card-head">
            <div class="card-title">Today's Attendance</div>
            <span class="live-chip"><span class="pulse-dot"></span>Live</span>
          </div>
          <div class="card-body">
            <div class="att-summary">
              <div class="att-ring-wrap">
                <svg viewBox="0 0 80 80" class="att-ring">
                  <circle cx="40" cy="40" r="32" fill="none" stroke="var(--b2)" stroke-width="8"/>
                  <circle cx="40" cy="40" r="32" fill="none" stroke="#10b981" stroke-width="8" stroke-dasharray="176 25" stroke-dashoffset="50" stroke-linecap="round"/>
                  <text x="40" y="37" text-anchor="middle" fill="var(--t1)" font-size="12" font-weight="700" font-family="Plus Jakarta Sans">47</text>
                  <text x="40" y="48" text-anchor="middle" fill="var(--t3)" font-size="5.5" font-family="Plus Jakarta Sans">of 52</text>
                </svg>
              </div>
              <div class="att-stats">
                <div class="att-stat"><span class="as-dot" style="background:#10b981"></span><span class="as-label">Present</span><strong>47</strong></div>
                <div class="att-stat"><span class="as-dot" style="background:#ef4444"></span><span class="as-label">Absent</span><strong>3</strong></div>
                <div class="att-stat"><span class="as-dot" style="background:#f59e0b"></span><span class="as-label">Late</span><strong>2</strong></div>
                <div class="att-stat"><span class="as-dot" style="background:#8b5cf6"></span><span class="as-label">WFH</span><strong>5</strong></div>
              </div>
            </div>
            <div class="att-dept-list">
              <div class="dept-row"><span class="dept-name">Sales Team</span><div class="dept-bar-wrap"><div class="dept-bar" style="width:88%;background:#6366f1"></div></div><span class="dept-pct">88%</span></div>
              <div class="dept-row"><span class="dept-name">Dev Team</span><div class="dept-bar-wrap"><div class="dept-bar" style="width:95%;background:#10b981"></div></div><span class="dept-pct">95%</span></div>
              <div class="dept-row"><span class="dept-name">Design</span><div class="dept-bar-wrap"><div class="dept-bar" style="width:100%;background:#f59e0b"></div></div><span class="dept-pct">100%</span></div>
              <div class="dept-row"><span class="dept-name">HR/Ops</span><div class="dept-bar-wrap"><div class="dept-bar" style="width:75%;background:#06b6d4"></div></div><span class="dept-pct">75%</span></div>
            </div>
            <button class="btn-full-outline" onclick="navigate(event,'attendance')">View Full Report <i class="bi bi-arrow-right"></i></button>
          </div>
        </div>

        <!-- Recent Leads -->
        <div class="dash-card span-6">
          <div class="card-head">
            <div>
              <div class="card-title">Lead Pipeline</div>
              <div class="card-sub">147 total · 38 hot leads</div>
            </div>
            <div class="card-actions">
              <select class="filter-select"><option>All Sources</option><option>Website</option><option>Referral</option><option>LinkedIn</option></select>
              <button class="btn-primary-solid sm" onclick="openModal('addLeadModal')"><i class="bi bi-plus-lg"></i> Lead</button>
            </div>
          </div>
          <div class="table-wrap">
            <table class="data-table">
              <thead><tr><th>Lead</th><th>Source</th><th>Value</th><th>Stage</th><th>Owner</th><th>Action</th></tr></thead>
              <tbody>
                <tr>
                  <td><div class="lead-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">TC</div><div><div class="ln">TechCorp Solutions</div><div class="ls">techcorp@example.com</div></div></div></td>
                  <td><span class="src-tag website">Website</span></td>
                  <td><strong style="color:#10b981">₹18L</strong></td>
                  <td><span class="lead-stage hot">Hot 🔥</span></td>
                  <td><div class="mini-ava sm" style="background:linear-gradient(135deg,#f59e0b,#ef4444)">RK</div></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('leadDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-telephone-fill"></i></button></div></td>
                </tr>
                <tr>
                  <td><div class="lead-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#10b981,#06b6d4)">NF</div><div><div class="ln">Nexus Finance</div><div class="ls">info@nexusfinance.in</div></div></div></td>
                  <td><span class="src-tag referral">Referral</span></td>
                  <td><strong style="color:#10b981">₹24L</strong></td>
                  <td><span class="lead-stage warm">Warm</span></td>
                  <td><div class="mini-ava sm" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">PS</div></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('leadDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-telephone-fill"></i></button></div></td>
                </tr>
                <tr>
                  <td><div class="lead-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#f59e0b,#ef4444)">RM</div><div><div class="ln">RetailMax India</div><div class="ls">sales@retailmax.com</div></div></div></td>
                  <td><span class="src-tag linkedin">LinkedIn</span></td>
                  <td><strong style="color:#10b981">₹9.5L</strong></td>
                  <td><span class="lead-stage cold">Cold</span></td>
                  <td><div class="mini-ava sm" style="background:linear-gradient(135deg,#06b6d4,#6366f1)">NK</div></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('leadDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-telephone-fill"></i></button></div></td>
                </tr>
                <tr>
                  <td><div class="lead-cell"><div class="mini-ava" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)">DF</div><div><div class="ln">DataFirst Corp</div><div class="ls">cto@datafirst.io</div></div></div></td>
                  <td><span class="src-tag website">Website</span></td>
                  <td><strong style="color:#10b981">₹32L</strong></td>
                  <td><span class="lead-stage hot">Hot 🔥</span></td>
                  <td><div class="mini-ava sm" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)">SM</div></td>
                  <td><div class="row-actions"><button class="ra-btn" onclick="openModal('leadDetailModal')"><i class="bi bi-eye-fill"></i></button><button class="ra-btn"><i class="bi bi-telephone-fill"></i></button></div></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Sales Orders + Team Velocity -->
        <div class="dash-card span-6">
          <div class="card-head">
            <div>
              <div class="card-title">Recent Orders</div>
              <div class="card-sub">₹48.6L this month</div>
            </div>
            <div class="card-actions">
              <select class="filter-select"><option>All Status</option><option>Paid</option><option>Pending</option><option>Overdue</option></select>
              <button class="btn-primary-solid sm" onclick="openModal('addOrderModal')"><i class="bi bi-plus-lg"></i> Order</button>
            </div>
          </div>
          <div class="table-wrap">
            <table class="data-table">
              <thead><tr><th>Order ID</th><th>Client</th><th>Amount</th><th>Date</th><th>Status</th><th></th></tr></thead>
              <tbody>
                <tr>
                  <td><span class="mono">#ORD-2847</span></td>
                  <td>TechCorp Pvt Ltd</td>
                  <td><strong>₹8.5L</strong></td>
                  <td style="color:var(--t3)">Nov 18</td>
                  <td><span class="status-pill paid">Paid</span></td>
                  <td><button class="ra-btn" onclick="openModal('orderDetailModal')"><i class="bi bi-eye-fill"></i></button></td>
                </tr>
                <tr>
                  <td><span class="mono">#ORD-2846</span></td>
                  <td>Nexus Finance</td>
                  <td><strong>₹12.2L</strong></td>
                  <td style="color:var(--t3)">Nov 16</td>
                  <td><span class="status-pill pending">Pending</span></td>
                  <td><button class="ra-btn" onclick="openModal('orderDetailModal')"><i class="bi bi-eye-fill"></i></button></td>
                </tr>
                <tr>
                  <td><span class="mono">#ORD-2845</span></td>
                  <td>RetailMax India</td>
                  <td><strong>₹6.9L</strong></td>
                  <td style="color:var(--t3)">Nov 14</td>
                  <td><span class="status-pill paid">Paid</span></td>
                  <td><button class="ra-btn" onclick="openModal('orderDetailModal')"><i class="bi bi-eye-fill"></i></button></td>
                </tr>
                <tr>
                  <td><span class="mono">#ORD-2844</span></td>
                  <td>DataFirst Corp</td>
                  <td><strong>₹18.0L</strong></td>
                  <td style="color:var(--t3)">Nov 12</td>
                  <td><span class="status-pill overdue">Overdue</span></td>
                  <td><button class="ra-btn" onclick="openModal('orderDetailModal')"><i class="bi bi-eye-fill"></i></button></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Team Activity Feed -->
        <div class="dash-card span-4">
          <div class="card-head">
            <div class="card-title">Team Activity</div>
            <span class="live-chip"><span class="pulse-dot"></span>Live</span>
          </div>
          <div class="card-body">
            <div class="activity-feed">
              <div class="af-item">
                <div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);flex-shrink:0">AK</div>
                <div class="af-body">
                  <div class="af-text"><strong>Arjun Kumar</strong> pushed 3 commits to <span class="af-link">orion-ecom</span></div>
                  <div class="af-time"><i class="bi bi-git"></i> 8 min ago</div>
                </div>
              </div>
              <div class="af-item">
                <div class="mini-ava" style="background:linear-gradient(135deg,#10b981,#06b6d4);flex-shrink:0">PS</div>
                <div class="af-body">
                  <div class="af-text"><strong>Priya Sharma</strong> closed lead <span class="af-link">Nexus Finance</span> — ₹24L 🎉</div>
                  <div class="af-time"><i class="bi bi-trophy-fill"></i> 22 min ago</div>
                </div>
              </div>
              <div class="af-item">
                <div class="mini-ava" style="background:linear-gradient(135deg,#f59e0b,#ef4444);flex-shrink:0">RV</div>
                <div class="af-body">
                  <div class="af-text"><strong>Ravi Verma</strong> updated status of <span class="af-link">HR Portal</span> task</div>
                  <div class="af-time"><i class="bi bi-kanban"></i> 1 hr ago</div>
                </div>
              </div>
              <div class="af-item">
                <div class="mini-ava" style="background:linear-gradient(135deg,#06b6d4,#6366f1);flex-shrink:0">NK</div>
                <div class="af-body">
                  <div class="af-text"><strong>Neha Kapoor</strong> marked attendance via <span class="af-link">Sales Panel</span></div>
                  <div class="af-time"><i class="bi bi-clock"></i> 2 hrs ago</div>
                </div>
              </div>
              <div class="af-item">
                <div class="mini-ava" style="background:linear-gradient(135deg,#8b5cf6,#ec4899);flex-shrink:0">SM</div>
                <div class="af-body">
                  <div class="af-text"><strong>Sunita Mehta</strong> created proposal for <span class="af-link">DataFirst Corp</span></div>
                  <div class="af-time"><i class="bi bi-file-earmark-text"></i> 3 hrs ago</div>
                </div>
              </div>
              <div class="af-item">
                <div class="mini-ava" style="background:linear-gradient(135deg,#ef4444,#f59e0b);flex-shrink:0">MG</div>
                <div class="af-body">
                  <div class="af-text"><strong>Mohit Gupta</strong> completed code review for <span class="af-link">FinanceMe</span></div>
                  <div class="af-time"><i class="bi bi-check2-circle"></i> 4 hrs ago</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Developer Velocity -->
        <div class="dash-card span-4">
          <div class="card-head">
            <div class="card-title">Developer Velocity</div>
            <div class="card-sub">Sprint 12 · Nov 1–15</div>
          </div>
          <div class="card-body">
            <div class="dev-velocity">
              <div class="vel-item">
                <div class="vel-ava" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">AK</div>
                <div class="vel-info"><div class="vel-name">Arjun Kumar</div><div class="vel-role">Frontend</div></div>
                <div class="vel-bars">
                  <div class="vel-bar-wrap"><div class="vel-bar" style="width:88%;background:#6366f1"></div></div>
                  <span class="vel-pct">22 tasks</span>
                </div>
              </div>
              <div class="vel-item">
                <div class="vel-ava" style="background:linear-gradient(135deg,#10b981,#06b6d4)">MG</div>
                <div class="vel-info"><div class="vel-name">Mohit Gupta</div><div class="vel-role">Backend</div></div>
                <div class="vel-bars">
                  <div class="vel-bar-wrap"><div class="vel-bar" style="width:76%;background:#10b981"></div></div>
                  <span class="vel-pct">19 tasks</span>
                </div>
              </div>
              <div class="vel-item">
                <div class="vel-ava" style="background:linear-gradient(135deg,#f59e0b,#ef4444)">RV</div>
                <div class="vel-info"><div class="vel-name">Ravi Verma</div><div class="vel-role">Full Stack</div></div>
                <div class="vel-bars">
                  <div class="vel-bar-wrap"><div class="vel-bar" style="width:64%;background:#f59e0b"></div></div>
                  <span class="vel-pct">16 tasks</span>
                </div>
              </div>
              <div class="vel-item">
                <div class="vel-ava" style="background:linear-gradient(135deg,#06b6d4,#6366f1)">SA</div>
                <div class="vel-info"><div class="vel-name">Sneha Agarwal</div><div class="vel-role">Mobile</div></div>
                <div class="vel-bars">
                  <div class="vel-bar-wrap"><div class="vel-bar" style="width:52%;background:#06b6d4"></div></div>
                  <span class="vel-pct">13 tasks</span>
                </div>
              </div>
              <div class="vel-item">
                <div class="vel-ava" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)">KP</div>
                <div class="vel-info"><div class="vel-name">Kiran Patel</div><div class="vel-role">DevOps</div></div>
                <div class="vel-bars">
                  <div class="vel-bar-wrap"><div class="vel-bar" style="width:44%;background:#8b5cf6"></div></div>
                  <span class="vel-pct">11 tasks</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sales Funnel -->
        <div class="dash-card span-4">
          <div class="card-head">
            <div class="card-title">Sales Funnel</div>
            <div class="card-sub">Conversion by stage</div>
          </div>
          <div class="card-body">
            <div class="funnel">
              <div class="funnel-stage" style="--w:100%;--col:#6366f1"><div class="fs-bar"><span class="fs-label">Awareness</span><span class="fs-val">1,247</span></div></div>
              <div class="funnel-stage" style="--w:68%;--col:#8b5cf6"><div class="fs-bar"><span class="fs-label">Interest</span><span class="fs-val">848</span></div></div>
              <div class="funnel-stage" style="--w:48%;--col:#a78bfa"><div class="fs-bar"><span class="fs-label">Proposal</span><span class="fs-val">598</span></div></div>
              <div class="funnel-stage" style="--w:30%;--col:#f59e0b"><div class="fs-bar"><span class="fs-label">Negotiation</span><span class="fs-val">374</span></div></div>
              <div class="funnel-stage" style="--w:19%;--col:#10b981"><div class="fs-bar"><span class="fs-label">Closed Won</span><span class="fs-val">238</span></div></div>
            </div>
            <div class="funnel-rate">Overall Conversion: <strong style="color:#10b981">19.1%</strong></div>
          </div>
        </div>

      </div><!-- end dash-grid -->
    </div>
