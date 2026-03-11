<div class="modal-backdrop" id="addOrderModal">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div class="modal-hd"><span>Create New Order</span><button class="modal-close" onclick="closeModal('addOrderModal')"><i class="bi bi-x-lg"></i></button></div>
    <div class="modal-bd">
      <div class="form-grid">
        <div class="form-row"><label class="form-lbl">Client *</label><input type="text" class="form-inp" placeholder="Client name"></div>
        <div class="form-row"><label class="form-lbl">Order Value *</label><input type="text" class="form-inp" placeholder="₹ Amount"></div>
        <div class="form-row"><label class="form-lbl">Service/Product</label><input type="text" class="form-inp" placeholder="What are we delivering?"></div>
        <div class="form-row"><label class="form-lbl">Payment Terms</label><select class="form-inp"><option>Full Advance</option><option>50-50</option><option>Milestone</option><option>Net 30</option></select></div>
        <div class="form-row"><label class="form-lbl">Delivery Date</label><input type="date" class="form-inp"></div>
        <div class="form-row"><label class="form-lbl">Linked Project</label><select class="form-inp"><option>None</option><option>Orion E-Commerce</option><option>FinanceMe App</option><option>SmartCart PWA</option></select></div>
      </div>
    </div>
    <div class="modal-ft">
      <button class="btn-ghost" onclick="closeModal('addOrderModal')">Cancel</button>
      <button class="btn-primary-solid" onclick="closeModal('addOrderModal');showToast('success','Order created!','bi-bag-check-fill')"><i class="bi bi-plus-lg"></i> Create Order</button>
    </div>
  </div>
</div>
