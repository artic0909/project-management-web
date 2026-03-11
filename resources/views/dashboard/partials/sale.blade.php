<div class="page" id="page-sales-dash" style="display:block;">
      <div class="page-header"><h1 class="page-title">My Sales Dashboard</h1><p class="page-desc">Personal performance · Rahul Kumar · Sales Executive</p></div>
      <div class="kpi-grid" style="grid-template-columns:repeat(4,1fr)">
        <div class="kpi-card" style="--kpi-accent:#6366f1"><div class="kpi-top"><div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#6366f1"><i class="bi bi-trophy-fill"></i></div><span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 24%</span></div><div class="kpi-value">₹18.4L</div><div class="kpi-label">My Revenue</div></div>
        <div class="kpi-card" style="--kpi-accent:#10b981"><div class="kpi-top"><div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i class="bi bi-person-lines-fill"></i></div><span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 12%</span></div><div class="kpi-value">28</div><div class="kpi-label">My Leads</div></div>
        <div class="kpi-card" style="--kpi-accent:#f59e0b"><div class="kpi-top"><div class="kpi-icon" style="background:rgba(245,158,11,.15);color:#f59e0b"><i class="bi bi-bag-fill"></i></div><span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 8%</span></div><div class="kpi-value">12</div><div class="kpi-label">Orders Closed</div></div>
        <div class="kpi-card" style="--kpi-accent:#06b6d4"><div class="kpi-top"><div class="kpi-icon" style="background:rgba(6,182,212,.15);color:#06b6d4"><i class="bi bi-bullseye"></i></div><span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 5%</span></div><div class="kpi-value">73%</div><div class="kpi-label">Target Achieved</div></div>
      </div>
      <div class="stub-banner info" style="margin-top:16px"><i class="bi bi-speedometer2"></i><div><strong>Sales Dashboard</strong><span>My full pipeline, call logs, follow-ups and commission tracker.</span></div></div>
    </div>
    <div class="page" id="page-add-lead">
      <div class="page-header"><h1 class="page-title">Add New Lead</h1></div>
      <div class="dash-card" style="max-width:700px">
        <div class="card-head"><div class="card-title">Lead Information</div></div>
        <div class="card-body">
          <div class="form-grid">
            <div class="form-row"><label class="form-lbl">Company Name *</label><input type="text" class="form-inp" placeholder="e.g. TechCorp Solutions"></div>
            <div class="form-row"><label class="form-lbl">Contact Person *</label><input type="text" class="form-inp" placeholder="Full name"></div>
            <div class="form-row"><label class="form-lbl">Email</label><input type="email" class="form-inp" placeholder="contact@company.com"></div>
            <div class="form-row"><label class="form-lbl">Phone</label><input type="tel" class="form-inp" placeholder="+91 XXXXX XXXXX"></div>
            <div class="form-row"><label class="form-lbl">Lead Source</label><select class="form-inp"><option>Website</option><option>Referral</option><option>LinkedIn</option><option>Cold Call</option><option>Event</option></select></div>
            <div class="form-row"><label class="form-lbl">Estimated Value</label><input type="text" class="form-inp" placeholder="₹ Amount"></div>
            <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Notes</label><textarea class="form-inp" rows="3" placeholder="Initial discussion notes…"></textarea></div>
          </div>
          <div class="form-actions"><button class="btn-ghost">Cancel</button><button class="btn-primary-solid" onclick="showToast('success','Lead added successfully!','bi-person-check-fill')"><i class="bi bi-plus-lg"></i> Add Lead</button></div>
        </div>
      </div>
    </div>
    <div class="page" id="page-add-order">
      <div class="page-header"><h1 class="page-title">New Order</h1></div>
      <div class="dash-card" style="max-width:700px">
        <div class="card-head"><div class="card-title">Order Details</div></div>
        <div class="card-body">
          <div class="form-grid">
            <div class="form-row"><label class="form-lbl">Client Name *</label><input type="text" class="form-inp" placeholder="Select or type client"></div>
            <div class="form-row"><label class="form-lbl">Project/Service *</label><input type="text" class="form-inp" placeholder="What are we delivering?"></div>
            <div class="form-row"><label class="form-lbl">Order Value *</label><input type="text" class="form-inp" placeholder="₹ Amount"></div>
            <div class="form-row"><label class="form-lbl">Payment Terms</label><select class="form-inp"><option>Full Advance</option><option>50-50</option><option>Milestone</option><option>Net 30</option></select></div>
            <div class="form-row"><label class="form-lbl">Delivery Date</label><input type="date" class="form-inp"></div>
            <div class="form-row"><label class="form-lbl">Priority</label><select class="form-inp"><option>Normal</option><option>High</option><option>Urgent</option></select></div>
            <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Scope of Work</label><textarea class="form-inp" rows="3" placeholder="Describe deliverables…"></textarea></div>
          </div>
          <div class="form-actions"><button class="btn-ghost">Cancel</button><button class="btn-primary-solid" onclick="showToast('success','Order created!','bi-bag-check-fill')"><i class="bi bi-plus-lg"></i> Create Order</button></div>
        </div>
      </div>
    </div>
    <div class="page" id="page-targets"><div class="page-header"><h1 class="page-title">My Targets</h1></div><div class="stub-banner info"><i class="bi bi-bullseye"></i><div><strong>Target Tracker</strong><span>Monthly and quarterly targets with achievement metrics.</span></div></div></div>