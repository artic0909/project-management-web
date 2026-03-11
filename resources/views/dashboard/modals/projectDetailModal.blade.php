<div class="modal-backdrop" id="projectDetailModal">
  <div class="modal-box lg-box" onclick="event.stopPropagation()">
    <div class="modal-hd">
      <div style="display:flex;align-items:center;gap:12px">
        <div class="proj-icon" style="background:rgba(99,102,241,.15);color:#6366f1;width:36px;height:36px;font-size:13px">OR</div>
        <div><span style="font-size:16px;font-weight:700">Orion E-Commerce</span><div style="font-size:12px;color:var(--t3)">#PRJ-001 · TechCorp Pvt Ltd</div></div>
      </div>
      <button class="modal-close" onclick="closeModal('projectDetailModal')"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="modal-bd">
      <div class="detail-grid">
        <div class="detail-col">
          <div class="detail-section">
            <div class="ds-label">Progress</div>
            <div style="display:flex;align-items:center;gap:12px;margin-top:8px">
              <div class="prog-bar-wrap" style="flex:1;height:8px"><div class="prog-fill" style="width:78%;background:#6366f1;height:8px;border-radius:4px"></div></div>
              <strong style="color:#6366f1">78%</strong>
            </div>
          </div>
          <div class="detail-kpis">
            <div class="dk-item"><div class="dk-val">₹8.5L</div><div class="dk-lbl">Budget</div></div>
            <div class="dk-item"><div class="dk-val" style="color:#10b981">₹6.2L</div><div class="dk-lbl">Spent</div></div>
            <div class="dk-item"><div class="dk-val" style="color:#f59e0b">Dec 15</div><div class="dk-lbl">Deadline</div></div>
            <div class="dk-item"><div class="dk-val">8</div><div class="dk-lbl">Devs</div></div>
          </div>
          <div class="detail-section">
            <div class="ds-label">Team Members</div>
            <div class="member-chips">
              <div class="mc-item"><div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">AK</div>Arjun Kumar <span class="mc-role">Lead</span></div>
              <div class="mc-item"><div class="mini-ava" style="background:linear-gradient(135deg,#10b981,#06b6d4)">MG</div>Mohit Gupta <span class="mc-role">Backend</span></div>
              <div class="mc-item"><div class="mini-ava" style="background:linear-gradient(135deg,#f59e0b,#ef4444)">SA</div>Sneha Agarwal <span class="mc-role">Mobile</span></div>
              <div class="mc-item"><div class="mini-ava" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)">KP</div>Kiran Patel <span class="mc-role">DevOps</span></div>
            </div>
          </div>
        </div>
        <div class="detail-col">
          <div class="detail-section">
            <div class="ds-label">Recent Activity</div>
            <div class="mini-timeline">
              <div class="mt-item green"><div class="mt-dot"></div><div><div class="mt-text">Payment milestone 2 received</div><div class="mt-time">Nov 15</div></div></div>
              <div class="mt-item blue"><div class="mt-dot"></div><div><div class="mt-text">Sprint 4 review completed</div><div class="mt-time">Nov 12</div></div></div>
              <div class="mt-item orange"><div class="mt-dot"></div><div><div class="mt-text">Design handover — checkout flow</div><div class="mt-time">Nov 8</div></div></div>
              <div class="mt-item purple"><div class="mt-dot"></div><div><div class="mt-text">Backend API v2 deployed</div><div class="mt-time">Nov 5</div></div></div>
            </div>
          </div>
          <div class="detail-section">
            <div class="ds-label">Status Update</div>
            <select class="form-inp" style="margin-top:8px"><option>In Progress</option><option>Review</option><option>Completed</option><option>On Hold</option></select>
            <textarea class="form-inp" rows="2" style="margin-top:8px" placeholder="Add a note…"></textarea>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-ft">
      <button class="btn-ghost" onclick="closeModal('projectDetailModal')">Close</button>
      <button class="btn-ghost danger-ghost"><i class="bi bi-trash-fill"></i> Archive</button>
      <button class="btn-primary-solid" onclick="closeModal('projectDetailModal');showToast('success','Project updated!','bi-kanban-fill')"><i class="bi bi-check-lg"></i> Save Changes</button>
    </div>
  </div>
</div>
