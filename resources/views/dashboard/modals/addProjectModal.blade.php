<div class="modal-backdrop" id="addProjectModal">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div class="modal-hd"><span>New Project</span><button class="modal-close" onclick="closeModal('addProjectModal')"><i class="bi bi-x-lg"></i></button></div>
    <div class="modal-bd">
      <div class="form-grid">
        <div class="form-row"><label class="form-lbl">Project Name *</label><input type="text" class="form-inp" placeholder="e.g. E-Commerce Platform"></div>
        <div class="form-row"><label class="form-lbl">Client *</label><input type="text" class="form-inp" placeholder="Client company name"></div>
        <div class="form-row"><label class="form-lbl">Team Lead</label><select class="form-inp"><option>Arjun Kumar</option><option>Priya Sharma</option><option>Ravi Verma</option><option>Neha Kapoor</option></select></div>
        <div class="form-row"><label class="form-lbl">Tech Stack</label><input type="text" class="form-inp" placeholder="React, Node, MongoDB…"></div>
        <div class="form-row"><label class="form-lbl">Start Date</label><input type="date" class="form-inp"></div>
        <div class="form-row"><label class="form-lbl">Deadline</label><input type="date" class="form-inp"></div>
        <div class="form-row"><label class="form-lbl">Budget (₹)</label><input type="text" class="form-inp" placeholder="e.g. 8,50,000"></div>
        <div class="form-row"><label class="form-lbl">Priority</label><select class="form-inp"><option>Normal</option><option>High</option><option>Critical</option></select></div>
        <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Description</label><textarea class="form-inp" rows="3" placeholder="Project scope and objectives…"></textarea></div>
      </div>
    </div>
    <div class="modal-ft">
      <button class="btn-ghost" onclick="closeModal('addProjectModal')">Cancel</button>
      <button class="btn-primary-solid" onclick="closeModal('addProjectModal');showToast('success','Project created!','bi-kanban-fill')"><i class="bi bi-plus-lg"></i> Create Project</button>
    </div>
  </div>
</div>
