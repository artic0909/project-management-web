<div class="modal-backdrop" id="addMemberModal">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div class="modal-hd"><span>Add Team Member</span><button class="modal-close" onclick="closeModal('addMemberModal')"><i class="bi bi-x-lg"></i></button></div>
    <div class="modal-bd">
      <div class="form-grid">
        <div class="form-row"><label class="form-lbl">Full Name *</label><input type="text" class="form-inp" placeholder="Employee full name"></div>
        <div class="form-row"><label class="form-lbl">Email *</label><input type="email" class="form-inp" placeholder="name@oriontech.in"></div>
        <div class="form-row"><label class="form-lbl">Department</label><select class="form-inp"><option>Development</option><option>Sales</option><option>Design</option><option>HR/Ops</option></select></div>
        <div class="form-row"><label class="form-lbl">Role/Designation</label><select class="form-inp"><option>Developer</option><option>Sales Executive</option><option>Designer</option><option>Manager</option><option>Admin</option></select></div>
        <div class="form-row"><label class="form-lbl">Panel Access</label><select class="form-inp"><option>Sales Panel</option><option>Developer Panel</option><option>Admin Panel</option></select></div>
        <div class="form-row"><label class="form-lbl">Joining Date</label><input type="date" class="form-inp"></div>
      </div>
    </div>
    <div class="modal-ft">
      <button class="btn-ghost" onclick="closeModal('addMemberModal')">Cancel</button>
      <button class="btn-primary-solid" onclick="closeModal('addMemberModal');showToast('success','Member added! Invite sent.','bi-person-check-fill')"><i class="bi bi-person-plus-fill"></i> Add Member</button>
    </div>
  </div>
</div>
