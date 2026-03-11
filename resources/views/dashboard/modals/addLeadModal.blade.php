<div class="modal-backdrop" id="addLeadModal">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div class="modal-hd"><span>Add New Lead</span><button class="modal-close" onclick="closeModal('addLeadModal')"><i class="bi bi-x-lg"></i></button></div>
    <div class="modal-bd">
      <div class="form-grid">
        <div class="form-row"><label class="form-lbl">Company *</label><input type="text" class="form-inp" placeholder="Company name"></div>
        <div class="form-row"><label class="form-lbl">Contact Person *</label><input type="text" class="form-inp" placeholder="Full name"></div>
        <div class="form-row"><label class="form-lbl">Email</label><input type="email" class="form-inp" placeholder="email@company.com"></div>
        <div class="form-row"><label class="form-lbl">Phone</label><input type="tel" class="form-inp" placeholder="+91 XXXXX XXXXX"></div>
        <div class="form-row"><label class="form-lbl">Source</label><select class="form-inp"><option>Website</option><option>Referral</option><option>LinkedIn</option><option>Cold Call</option></select></div>
        <div class="form-row"><label class="form-lbl">Stage</label><select class="form-inp"><option>Cold</option><option>Warm</option><option>Hot</option></select></div>
        <div class="form-row"><label class="form-lbl">Est. Value (₹)</label><input type="text" class="form-inp" placeholder="Expected deal value"></div>
        <div class="form-row"><label class="form-lbl">Assign To</label><select class="form-inp"><option>Rahul Kumar</option><option>Priya Sharma</option><option>Neha Kapoor</option></select></div>
      </div>
    </div>
    <div class="modal-ft">
      <button class="btn-ghost" onclick="closeModal('addLeadModal')">Cancel</button>
      <button class="btn-primary-solid" onclick="closeModal('addLeadModal');showToast('success','Lead added!','bi-person-check-fill')"><i class="bi bi-plus-lg"></i> Add Lead</button>
    </div>
  </div>
</div>
