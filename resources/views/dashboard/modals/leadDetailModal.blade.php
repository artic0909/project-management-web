<div class="modal-backdrop" id="leadDetailModal">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div class="modal-hd"><span>Lead Detail — TechCorp Solutions</span><button class="modal-close" onclick="closeModal('leadDetailModal')"><i class="bi bi-x-lg"></i></button></div>
    <div class="modal-bd">
      <div class="detail-kpis" style="margin-bottom:20px">
        <div class="dk-item"><div class="dk-val">₹18L</div><div class="dk-lbl">Est. Value</div></div>
        <div class="dk-item"><div class="dk-val" style="color:#ef4444">Hot 🔥</div><div class="dk-lbl">Stage</div></div>
        <div class="dk-item"><div class="dk-val">Website</div><div class="dk-lbl">Source</div></div>
        <div class="dk-item"><div class="dk-val">14 days</div><div class="dk-lbl">Age</div></div>
      </div>
      <div class="form-grid">
        <div class="form-row"><label class="form-lbl">Contact</label><input class="form-inp" value="Vikram Bhatia" readonly></div>
        <div class="form-row"><label class="form-lbl">Email</label><input class="form-inp" value="vikram@techcorp.com" readonly></div>
        <div class="form-row"><label class="form-lbl">Phone</label><input class="form-inp" value="+91 98765 43210" readonly></div>
        <div class="form-row"><label class="form-lbl">Move to Stage</label><select class="form-inp"><option>Hot</option><option>Proposal Sent</option><option>Negotiation</option><option>Closed Won</option><option>Closed Lost</option></select></div>
        <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Follow-up Note</label><textarea class="form-inp" rows="2" placeholder="Add follow-up note…"></textarea></div>
      </div>
    </div>
    <div class="modal-ft">
      <button class="btn-ghost" onclick="closeModal('leadDetailModal')">Close</button>
      <button class="btn-primary-solid" onclick="closeModal('leadDetailModal');showToast('success','Lead updated!','bi-person-check-fill')">Update Lead</button>
    </div>
  </div>
</div>
