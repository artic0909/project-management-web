<div class="modal-backdrop" id="quickAddModal">
  <div class="modal-box sm-box" onclick="event.stopPropagation()">
    <div class="modal-hd"><span>Quick Add</span><button class="modal-close" onclick="closeModal('quickAddModal')"><i class="bi bi-x-lg"></i></button></div>
    <div class="modal-bd">
      <div class="quick-add-grid">
        <button class="qa-btn" onclick="closeModal('quickAddModal');openModal('addProjectModal')"><i class="bi bi-kanban-fill"></i><span>Project</span></button>
        <button class="qa-btn" onclick="closeModal('quickAddModal');openModal('addLeadModal')"><i class="bi bi-person-plus-fill"></i><span>Lead</span></button>
        <button class="qa-btn" onclick="closeModal('quickAddModal');openModal('addOrderModal')"><i class="bi bi-bag-plus-fill"></i><span>Order</span></button>
        <button class="qa-btn" onclick="closeModal('quickAddModal');openModal('addMemberModal')"><i class="bi bi-people-fill"></i><span>Member</span></button>
        <button class="qa-btn" onclick="closeModal('quickAddModal');showToast('info','Task creation coming soon','bi-check2-square')"><i class="bi bi-check2-square"></i><span>Task</span></button>
        <button class="qa-btn" onclick="closeModal('quickAddModal');showToast('info','Invoice creation coming soon','bi-receipt')"><i class="bi bi-receipt"></i><span>Invoice</span></button>
      </div>
    </div>
  </div>
</div>
