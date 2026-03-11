<div class="page hidden" id="page-settings">
      <div class="page-header"><h1 class="page-title">Settings</h1><p class="page-desc">Configure platform preferences</p></div>
      <div class="settings-grid">
        <div class="dash-card">
          <div class="card-head"><div class="card-title">Company Profile</div></div>
          <div class="card-body">
            <div class="form-row"><label class="form-lbl">Company Name</label><input type="text" class="form-inp" value="Orion Technologies Pvt Ltd"></div>
            <div class="form-row"><label class="form-lbl">Email Domain</label><input type="text" class="form-inp" value="oriontech.in"></div>
            <div class="form-row"><label class="form-lbl">GST Number</label><input type="text" class="form-inp" value="27AABCO1234F1Z5"></div>
            <div class="form-row"><label class="form-lbl">Timezone</label><select class="form-inp"><option>IST (UTC+5:30)</option><option>UTC</option></select></div>
            <button class="btn-primary-solid" onclick="showToast('success','Settings saved!','bi-check-circle-fill')">Save Changes</button>
          </div>
        </div>
        <div class="dash-card">
          <div class="card-head"><div class="card-title">Notifications</div></div>
          <div class="card-body">
            <div class="setting-toggle-row"><div><div class="stl-name">New Lead Alert</div><div class="stl-desc">Notify admin when new lead added</div></div><label class="toggle-switch"><input type="checkbox" checked><span class="toggle-track"><span class="toggle-thumb"></span></span></label></div>
            <div class="setting-toggle-row"><div><div class="stl-name">Project Deadline</div><div class="stl-desc">Alert 48hrs before deadline</div></div><label class="toggle-switch"><input type="checkbox" checked><span class="toggle-track"><span class="toggle-thumb"></span></span></label></div>
            <div class="setting-toggle-row"><div><div class="stl-name">Attendance Alerts</div><div class="stl-desc">Late check-in notifications</div></div><label class="toggle-switch"><input type="checkbox"><span class="toggle-track"><span class="toggle-thumb"></span></span></label></div>
            <div class="setting-toggle-row"><div><div class="stl-name">Payment Received</div><div class="stl-desc">Instant payment notifications</div></div><label class="toggle-switch"><input type="checkbox" checked><span class="toggle-track"><span class="toggle-thumb"></span></span></label></div>
          </div>
        </div>
      </div>
    </div>
