<div class="modal-backdrop" id="orderDetailModal">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div class="modal-hd"><span>Order #ORD-2847</span><button class="modal-close" onclick="closeModal('orderDetailModal')"><i class="bi bi-x-lg"></i></button></div>
    <div class="modal-bd">
      <div class="detail-kpis" style="margin-bottom:20px">
        <div class="dk-item"><div class="dk-val">₹8.5L</div><div class="dk-lbl">Order Value</div></div>
        <div class="dk-item"><div class="dk-val" style="color:#10b981">Paid</div><div class="dk-lbl">Status</div></div>
        <div class="dk-item"><div class="dk-val">Nov 18</div><div class="dk-lbl">Date</div></div>
        <div class="dk-item"><div class="dk-val">Dec 15</div><div class="dk-lbl">Delivery</div></div>
      </div>
      <div class="form-grid">
        <div class="form-row"><label class="form-lbl">Client</label><input class="form-inp" value="TechCorp Pvt Ltd" readonly></div>
        <div class="form-row"><label class="form-lbl">Payment Terms</label><input class="form-inp" value="Full Advance" readonly></div>
        <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Linked Project</label><input class="form-inp" value="Orion E-Commerce #PRJ-001" readonly></div>
      </div>
    </div>
    <div class="modal-ft">
      <button class="btn-ghost" onclick="closeModal('orderDetailModal')">Close</button>
      <button class="btn-ghost" onclick="showToast('info','Invoice downloading…','bi-download')"><i class="bi bi-download"></i> Invoice</button>
      <button class="btn-primary-solid" onclick="closeModal('orderDetailModal');showToast('success','Order updated!','bi-bag-check-fill')">Update</button>
    </div>
  </div>
</div>
