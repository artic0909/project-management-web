<div class="page hidden" id="page-add-order">
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
