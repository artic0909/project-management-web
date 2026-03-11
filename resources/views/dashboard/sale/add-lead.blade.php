<div class="page hidden" id="page-add-lead">
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
