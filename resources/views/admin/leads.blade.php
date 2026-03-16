@extends('admin.layout.app')

@section('title', 'Add Leads')

@section('content')

<style>
    .multi-row {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
    }

    .multi-row:last-child {
        margin-bottom: 0;
    }

    .phone-wrap {
        display: flex;
        flex: 1;
        min-width: 0;
        border: 1px solid #dee2e6;
        /* matches your form-inp border */
        border-radius: 6px;
        /* matches your form-inp radius */
        overflow: hidden;
    }

    .country-sel {
        border: none;
        border-right: 1px solid #dee2e6;
        background: #f8f9fa;
        padding: 6px 4px 6px 8px;
        font-size: 13px;
        cursor: pointer;
        outline: none;
        font-family: inherit;
        width: 100px;
        flex-shrink: 0;
    }

    .phone-num-inp {
        border: none;
        padding: 6px 10px;
        font-size: 14px;
        font-family: inherit;
        flex: 1;
        min-width: 0;
        outline: none;
        background: transparent;
        color: inherit;
    }

    .multi-email-inp {
        flex: 1;
        min-width: 0;
    }

    .row-remove-btn {
        background: none;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        width: 28px;
        height: 28px;
        cursor: pointer;
        color: #aaa;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        padding: 0;
    }

    .row-remove-btn:hover {
        color: #e53e3e;
        border-color: #e53e3e;
        background: #fff5f5;
    }
</style>


<!-- ═══ PAGE CONTENT AREA ═══ -->
<main class="page-area" id="pageArea">

    <div class="page" id="page-dashboard">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Your All Leads</h1>
            </div>
        </div>


        <!-- MAIN GRID -->
        <div class="dash-grid">


            <div class="dash-card span-12">
                <div class="card-head">
                    <div>
                        <div class="card-title">Lead Pipeline</div>
                        <div class="card-sub">147 total · 38 hot leads</div>
                    </div>
                    <div class="card-actions mb-2">

                        <form class="global-search">
                            <i class="bi bi-search"></i>
                            <input type="text" placeholder="Search...">
                            <button type="submit" class="btn-primary-solid sm">Search</button>
                        </form>

                        <select class="filter-select">
                            <option>All Sources</option>
                            <option>Website</option>
                            <option>Referral</option>
                            <option>LinkedIn</option>
                        </select>
                        <button class="btn-primary-solid sm" onclick="openModal('addLeadModal')"><i class="bi bi-plus-lg"></i> Add Lead</button>
                    </div>
                </div>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Lead</th>
                                <th>Source</th>
                                <th>Contact Person</th>
                                <th>Service Need</th>
                                <th>Prioprity</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Assign To</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <div class="lead-cell">
                                        <div class="mini-ava" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)">DF</div>
                                        <div>
                                            <div class="ln">DataFirst Corp</div>
                                            <div class="ls">cto@datafirst.io</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="src-tag website">Website</span></td>
                                <td><strong style="color:#10b981">Abhishek</strong></td>
                                <td><strong style="color:#10b981">Website Design</strong></td>
                                <td>
                                    <span class="lead-stage hot">Hot 🔥</span>
                                    <span class="lead-stage cold">Cold</span>
                                    <span class="lead-stage warm">Warm</span>
                                </td>
                                <td><strong style="color:#10b981">Respond</strong></td>
                                <td>
                                    <div>
                                        <div class="ln">DataFirst Corp</div>
                                        <div class="ls">cto@datafirst.io</div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="ln">DataFirst Corp</div>
                                        <div class="ls">cto@datafirst.io</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <button class="ra-btn" onclick="openModal('leadDetailModal')"><i class="bi bi-eye-fill"></i></button>
                                        <button class="ra-btn"><i class="bi bi-envelope-fill"></i></button>
                                        <button class="ra-btn" onclick="openModal('followupModal')"><i class="bi bi-arrow-counterclockwise"></i></button>
                                        <button class="ra-btn" onclick="openModal('editLeadModal')"><i class="bi bi-pencil-fill"></i></button>
                                        <button class="ra-btn danger" onclick="openModal('deleteModal')"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="table-footer">
                    <span class="tf-info">Showing 5 of 24 Leads</span>
                    <div class="tf-pagination">
                        <button class="pg-btn"><i class="bi bi-chevron-left"></i></button>
                        <button class="pg-btn active">1</button>
                        <button class="pg-btn">2</button>
                        <button class="pg-btn">3</button>
                        <span class="pg-ellipsis">…</span>
                        <button class="pg-btn">5</button>
                        <button class="pg-btn"><i class="bi bi-chevron-right"></i></button>
                    </div>
                    <div class="tf-per-page">
                        <!-- <span>Rows:</span>
                            <select>
                                <option>5</option>
                                <option>10</option>
                                <option>25</option>
                            </select> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal-backdrop" id="addLeadModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd"><span>Add New Lead</span><button class="modal-close" onclick="closeModal('addLeadModal')"><i class="bi bi-x-lg"></i></button></div>
            <div class="modal-bd">
                <div class="form-grid">
                    <div class="form-row"><label class="form-lbl">Company *</label><input type="text" class="form-inp" placeholder="Company name"></div>
                    <div class="form-row"><label class="form-lbl">Contact Person *</label><input type="text" class="form-inp" placeholder="Full name"></div>
                    <div class="form-row"><label class="form-lbl">Business Type *</label><input type="text" class="form-inp" placeholder="Business type"></div>

                    <!-- EMAIL — multiple -->
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Email</label>
                        <div id="add-email-list"></div>
                        <button type="button" class="btn-ghost" style="margin-top:6px;padding:4px 10px;font-size:12px;" onclick="addEmailRow('add-email-list')"><i class="bi bi-plus-lg"></i> Add Email</button>
                    </div>

                    <!-- PHONE — multiple + country code -->
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Phone</label>
                        <div id="add-phone-list"></div>
                        <button type="button" class="btn-ghost" style="margin-top:6px;padding:4px 10px;font-size:12px;" onclick="addPhoneRow('add-phone-list')"><i class="bi bi-plus-lg"></i> Add Phone</button>
                    </div>

                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Service Need</label><select class="form-inp">
                            <option>Web</option>
                            <option>Design</option>
                            <option>Mark</option>
                        </select>
                    </div>

                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Address</label><textarea class="form-inp" rows="3" placeholder="Project scope and objectives…"></textarea></div>

                    <div class="form-row"><label class="form-lbl">Lead Source</label><select class="form-inp">
                            <option>Web</option>
                            <option>Linkedin</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <label class="form-lbl">Lead Priority</label>
                        <select class="form-inp">
                            <option>Cold</option>
                            <option>Warm</option>
                            <option>Hot</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <label class="form-lbl">Lead Status</label>
                        <select class="form-inp">
                            <option>Not Responding</option>
                            <option>Not Interested</option>
                            <option>Not Required</option>
                            <option>Location Isuue</option>
                            <option>Job</option>
                            <option>Not Inquired</option>
                            <option>Respond</option>
                            <option>Interested</option>
                            <option>Language Barrier</option>
                            <option>Booked</option>
                            <option>Budget Issue</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <label class="form-lbl">Assign To</label>
                        <select class="form-inp">
                            <option>Rahul Kumar</option>
                            <option>Priya Sharma</option>
                            <option>Neha Kapoor</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('addLeadModal')">Cancel</button>
                <button class="btn-primary-solid" onclick="closeModal('addLeadModal');showToast('success','Lead added!','bi-person-check-fill')"><i class="bi bi-plus-lg"></i> Add Lead</button>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal-backdrop" id="editLeadModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd"><span>Update Lead</span><button class="modal-close" onclick="closeModal('editLeadModal')"><i class="bi bi-x-lg"></i></button></div>
            <div class="modal-bd">
                <div class="form-grid">
                    <div class="form-row"><label class="form-lbl">Company *</label><input type="text" class="form-inp" placeholder="Company name"></div>
                    <div class="form-row"><label class="form-lbl">Contact Person *</label><input type="text" class="form-inp" placeholder="Full name"></div>
                    <div class="form-row"><label class="form-lbl">Business Type *</label><input type="text" class="form-inp" placeholder="Business type"></div>

                    <!-- EMAIL — multiple -->
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Email</label>
                        <div id="edit-email-list"></div>
                        <button type="button" class="btn-ghost" style="margin-top:6px;padding:4px 10px;font-size:12px;" onclick="addEmailRow('edit-email-list')"><i class="bi bi-plus-lg"></i> Add Email</button>
                    </div>

                    <!-- PHONE — multiple + country code -->
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Phone</label>
                        <div id="edit-phone-list"></div>
                        <button type="button" class="btn-ghost" style="margin-top:6px;padding:4px 10px;font-size:12px;" onclick="addPhoneRow('edit-phone-list')"><i class="bi bi-plus-lg"></i> Add Phone</button>
                    </div>

                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Service Need</label><select class="form-inp">
                            <option>Web</option>
                            <option>Design</option>
                            <option>Mark</option>
                        </select>
                    </div>

                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Address</label><textarea class="form-inp" rows="3" placeholder="Project scope and objectives…"></textarea></div>

                    <div class="form-row"><label class="form-lbl">Lead Source</label><select class="form-inp">
                            <option>Web</option>
                            <option>Linkedin</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <label class="form-lbl">Lead Priority</label>
                        <select class="form-inp">
                            <option>Cold</option>
                            <option>Warm</option>
                            <option>Hot</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <label class="form-lbl">Lead Status</label>
                        <select class="form-inp">
                            <option>Not Responding</option>
                            <option>Not Interested</option>
                            <option>Not Required</option>
                            <option>Location Isuue</option>
                            <option>Job</option>
                            <option>Not Inquired</option>
                            <option>Respond</option>
                            <option>Interested</option>
                            <option>Language Barrier</option>
                            <option>Booked</option>
                            <option>Budget Issue</option>
                        </select>
                    </div>

                    <div class="form-row"><label class="form-lbl">Assign To</label><select class="form-inp">
                            <option>Rahul Kumar</option>
                            <option>Priya Sharma</option>
                            <option>Neha Kapoor</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('editLeadModal')">Cancel</button>
                <button class="btn-primary-solid" onclick="closeModal('editLeadModal');showToast('success','Lead added!','bi-person-check-fill')"><i class="bi bi-plus-lg"></i> Update Lead</button>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Delete Lead</span>
                <button class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:#111827;">Are you sure?</h3>
                <p style="margin:0;font-size:14px;color:#6b7280;line-height:1.6;">Are you sure you want to delete this Lead?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                <button style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;" onclick="closeModal('deleteModal');showToast('success','Service Deleted!','bi-trash3-fill')">
                    <i class="bi bi-trash3-fill"></i> Delete Lead
                </button>
            </div>
        </div>
    </div>

    <!-- Lead Detail -->
    <div class="modal-backdrop" id="leadDetailModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd"><span>Lead Detail — TechCorp Solutions</span><button class="modal-close" onclick="closeModal('leadDetailModal')"><i class="bi bi-x-lg"></i></button></div>
            <div class="modal-bd">
                <div class="detail-kpis" style="margin-bottom:20px">
                    <div class="dk-item">
                        <div class="dk-val">12-02-2026</div>
                        <div class="dk-lbl">Created Date</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val" style="color:#ef4444">Hot 🔥</div>
                        <div class="dk-lbl">Priority</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val">Website</div>
                        <div class="dk-lbl">Service Need</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val">Linkedin</div>
                        <div class="dk-lbl">Lead Source</div>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-row"><label class="form-lbl">Contact</label><input class="form-inp" value="Vikram Bhatia" readonly></div>
                    <div class="form-row"><label class="form-lbl">Email</label><input class="form-inp" value="vikram@techcorp.com" readonly></div>
                    <div class="form-row"><label class="form-lbl">Phone</label><input class="form-inp" value="+91 98765 43210" readonly></div>
                    <div class="form-row">
                        <label class="form-lbl">Change Priority</label>
                        <select class="form-inp">
                            <option>Hot</option>
                            <option>Warm</option>
                            <option>Cold</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Change Lead Status</label>
                        <select class="form-inp">
                            <option>Not Responding</option>
                            <option>Not Interested</option>
                            <option>Not Required</option>
                            <option>Location Isuue</option>
                            <option>Job</option>
                            <option>Not Inquired</option>
                            <option>Respond</option>
                            <option>Interested</option>
                            <option>Language Barrier</option>
                            <option>Booked</option>
                            <option>Budget Issue</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Assign To</label>
                        <select class="form-inp">
                            <option>Rahul Kumar</option>
                            <option>Priya Sharma</option>
                            <option>Neha Kapoor</option>
                        </select>
                    </div>
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Convert Lead To</label>
                        <select class="form-inp">
                            <option>Order</option>
                            <option>Closed</option>
                        </select>
                    </div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Add Remark/Note</label><textarea class="form-inp" rows="2" placeholder="Add note…"></textarea></div>
                </div>
            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('leadDetailModal')">Close</button>
                <button class="btn-primary-solid" onclick="closeModal('leadDetailModal');showToast('success','Lead updated!','bi-person-check-fill')">Update Lead</button>
            </div>
        </div>
    </div>

    <!-- Followup Modal -->
    <div class="modal-backdrop" id="followupModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd"><span>Add Followup — TechCorp Solutions</span><button class="modal-close" onclick="closeModal('followupModal')"><i class="bi bi-x-lg"></i></button></div>
            <div class="modal-bd">

                <div class="form-row">
                    <label class="form-lbl">Followup Date *</label>
                    <input type="date" class="form-inp" id="followupDateInp">
                </div>
                <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Calling Followup Note</label><textarea class="form-inp" rows="2" placeholder="Add follow-up note…"></textarea></div>
                <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Message Followup Note</label><textarea class="form-inp" rows="2" placeholder="Add follow-up note…"></textarea></div>

                <!-- Followup History -->
                <div style="margin-top:20px;margin-bottom:12px;">
                    <span style="font-size:14px;font-weight:600;color:var(--t1);">Followup History</span>
                </div>

                <div style="height: 300px; overflow-y: auto;">

                    <div style="background:var(--bg2);border:1px solid var(--b1);border-radius:var(--r);overflow:hidden; margin-bottom: 5px;">

                        <!-- Card Body -->
                        <div style="padding:16px;">

                            <!-- Date pill -->
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                                <div style="display:flex;align-items:center;gap:6px;background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r-sm);padding:6px 12px;">
                                    <i class="bi bi-clock" style="font-size:12px;color:green;"></i>
                                    <span style="font-size:13px;font-weight:500;color:green;">12 Feb 2026</span>
                                </div>
                                <span style="font-size:12px;color:green;"><i class="bi bi-telephone-fill"></i> &nbsp;Follow-up date</span>
                            </div>

                            <!-- Note -->
                            <div style="border-left:2px solid green;padding-left:12px;border-radius:0;">
                                <p style="font-size:12px;font-weight:600;color:var(--t3);margin:0 0 4px;text-transform:uppercase;letter-spacing:0.05em;">Calling Note</p>
                                <p style="font-size:14px;color:var(--t2);margin:0;line-height:1.6;">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quae.
                                </p>
                            </div>

                        </div>
                    </div>

                    <div style="background:var(--bg2);border:1px solid var(--b1);border-radius:var(--r);overflow:hidden; margin-bottom: 5px;">

                        <!-- Card Body -->
                        <div style="padding:16px;">

                            <!-- Date pill -->
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                                <div style="display:flex;align-items:center;gap:6px;background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r-sm);padding:6px 12px;">
                                    <i class="bi bi-clock" style="font-size:12px;color:red;"></i>
                                    <span style="font-size:13px;font-weight:500;color:red;">12 Feb 2026</span>
                                </div>
                                <span style="font-size:12px;color:red;"><i class="bi bi-chat-fill"></i> &nbsp;Follow-up date</span>
                            </div>

                            <!-- Note -->
                            <div style="border-left:2px solid red;padding-left:12px;border-radius:0;">
                                <p style="font-size:12px;font-weight:600;color:var(--t3);margin:0 0 4px;text-transform:uppercase;letter-spacing:0.05em;">Message Note</p>
                                <p style="font-size:14px;color:var(--t2);margin:0;line-height:1.6;">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quae.
                                </p>
                            </div>

                        </div>
                    </div>

                    <div style="background:var(--bg2);border:1px solid var(--b1);border-radius:var(--r);overflow:hidden; margin-bottom: 5px;">

                        <!-- Card Body -->
                        <div style="padding:16px;">

                            <!-- Date pill -->
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                                <div style="display:flex;align-items:center;gap:6px;background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r-sm);padding:6px 12px;">
                                    <i class="bi bi-clock" style="font-size:12px;color:blue;"></i>
                                    <span style="font-size:13px;font-weight:500;color:blue;">12 Feb 2026</span>
                                </div>
                                <span style="font-size:12px;"><i class="bi bi-telephone-fill" style="color:green;"></i>/<i class="bi bi-chat-fill" style="color:red;"></i> &nbsp;Follow-up date</span>
                            </div>

                            <!-- Note -->
                            <div style="border-left:2px solid green;padding-left:12px;border-radius:0;">
                                <p style="font-size:12px;font-weight:600;color:var(--t3);margin:0 0 4px;text-transform:uppercase;letter-spacing:0.05em;">Calling Note</p>
                                <p style="font-size:14px;color:var(--t2);margin:0;line-height:1.6;">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quae.
                                </p>
                            </div>

                            <br>

                            <!-- Note -->
                            <div style="border-left:2px solid red;padding-left:12px;border-radius:0;">
                                <p style="font-size:12px;font-weight:600;color:var(--t3);margin:0 0 4px;text-transform:uppercase;letter-spacing:0.05em;">Message Note</p>
                                <p style="font-size:14px;color:var(--t2);margin:0;line-height:1.6;">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quae.
                                </p>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('followupModal')">Cancel</button>
                <button class="btn-primary-solid" onclick="closeModal('followupModal');showToast('success','Followup Added!','bi-person-check-fill')">
                    <i class="bi bi-plus-lg"></i> Update
                </button>
            </div>
        </div>
    </div>

</main>


<script>
    document.getElementById('followupDateInp').value = new Date().toISOString().split('T')[0];
    const COUNTRIES = [{
            f: "🇦🇫",
            n: "Afghanistan",
            c: "+93"
        }, {
            f: "🇦🇱",
            n: "Albania",
            c: "+355"
        }, {
            f: "🇩🇿",
            n: "Algeria",
            c: "+213"
        },
        {
            f: "🇦🇩",
            n: "Andorra",
            c: "+376"
        }, {
            f: "🇦🇴",
            n: "Angola",
            c: "+244"
        }, {
            f: "🇦🇷",
            n: "Argentina",
            c: "+54"
        },
        {
            f: "🇦🇲",
            n: "Armenia",
            c: "+374"
        }, {
            f: "🇦🇺",
            n: "Australia",
            c: "+61"
        }, {
            f: "🇦🇹",
            n: "Austria",
            c: "+43"
        },
        {
            f: "🇦🇿",
            n: "Azerbaijan",
            c: "+994"
        }, {
            f: "🇧🇸",
            n: "Bahamas",
            c: "+1-242"
        }, {
            f: "🇧🇭",
            n: "Bahrain",
            c: "+973"
        },
        {
            f: "🇧🇩",
            n: "Bangladesh",
            c: "+880"
        }, {
            f: "🇧🇾",
            n: "Belarus",
            c: "+375"
        }, {
            f: "🇧🇪",
            n: "Belgium",
            c: "+32"
        },
        {
            f: "🇧🇿",
            n: "Belize",
            c: "+501"
        }, {
            f: "🇧🇯",
            n: "Benin",
            c: "+229"
        }, {
            f: "🇧🇹",
            n: "Bhutan",
            c: "+975"
        },
        {
            f: "🇧🇴",
            n: "Bolivia",
            c: "+591"
        }, {
            f: "🇧🇦",
            n: "Bosnia",
            c: "+387"
        }, {
            f: "🇧🇼",
            n: "Botswana",
            c: "+267"
        },
        {
            f: "🇧🇷",
            n: "Brazil",
            c: "+55"
        }, {
            f: "🇧🇳",
            n: "Brunei",
            c: "+673"
        }, {
            f: "🇧🇬",
            n: "Bulgaria",
            c: "+359"
        },
        {
            f: "🇧🇫",
            n: "Burkina Faso",
            c: "+226"
        }, {
            f: "🇧🇮",
            n: "Burundi",
            c: "+257"
        }, {
            f: "🇨🇻",
            n: "Cabo Verde",
            c: "+238"
        },
        {
            f: "🇰🇭",
            n: "Cambodia",
            c: "+855"
        }, {
            f: "🇨🇲",
            n: "Cameroon",
            c: "+237"
        }, {
            f: "🇨🇦",
            n: "Canada",
            c: "+1"
        },
        {
            f: "🇨🇫",
            n: "Central African Rep.",
            c: "+236"
        }, {
            f: "🇹🇩",
            n: "Chad",
            c: "+235"
        }, {
            f: "🇨🇱",
            n: "Chile",
            c: "+56"
        },
        {
            f: "🇨🇳",
            n: "China",
            c: "+86"
        }, {
            f: "🇨🇴",
            n: "Colombia",
            c: "+57"
        }, {
            f: "🇰🇲",
            n: "Comoros",
            c: "+269"
        },
        {
            f: "🇨🇩",
            n: "Congo (DRC)",
            c: "+243"
        }, {
            f: "🇨🇬",
            n: "Congo (Rep.)",
            c: "+242"
        }, {
            f: "🇨🇷",
            n: "Costa Rica",
            c: "+506"
        },
        {
            f: "🇭🇷",
            n: "Croatia",
            c: "+385"
        }, {
            f: "🇨🇺",
            n: "Cuba",
            c: "+53"
        }, {
            f: "🇨🇾",
            n: "Cyprus",
            c: "+357"
        },
        {
            f: "🇨🇿",
            n: "Czech Republic",
            c: "+420"
        }, {
            f: "🇩🇰",
            n: "Denmark",
            c: "+45"
        }, {
            f: "🇩🇯",
            n: "Djibouti",
            c: "+253"
        },
        {
            f: "🇩🇴",
            n: "Dominican Rep.",
            c: "+1-809"
        }, {
            f: "🇪🇨",
            n: "Ecuador",
            c: "+593"
        }, {
            f: "🇪🇬",
            n: "Egypt",
            c: "+20"
        },
        {
            f: "🇸🇻",
            n: "El Salvador",
            c: "+503"
        }, {
            f: "🇬🇶",
            n: "Equatorial Guinea",
            c: "+240"
        }, {
            f: "🇪🇷",
            n: "Eritrea",
            c: "+291"
        },
        {
            f: "🇪🇪",
            n: "Estonia",
            c: "+372"
        }, {
            f: "🇸🇿",
            n: "Eswatini",
            c: "+268"
        }, {
            f: "🇪🇹",
            n: "Ethiopia",
            c: "+251"
        },
        {
            f: "🇫🇯",
            n: "Fiji",
            c: "+679"
        }, {
            f: "🇫🇮",
            n: "Finland",
            c: "+358"
        }, {
            f: "🇫🇷",
            n: "France",
            c: "+33"
        },
        {
            f: "🇬🇦",
            n: "Gabon",
            c: "+241"
        }, {
            f: "🇬🇲",
            n: "Gambia",
            c: "+220"
        }, {
            f: "🇬🇪",
            n: "Georgia",
            c: "+995"
        },
        {
            f: "🇩🇪",
            n: "Germany",
            c: "+49"
        }, {
            f: "🇬🇭",
            n: "Ghana",
            c: "+233"
        }, {
            f: "🇬🇷",
            n: "Greece",
            c: "+30"
        },
        {
            f: "🇬🇹",
            n: "Guatemala",
            c: "+502"
        }, {
            f: "🇬🇳",
            n: "Guinea",
            c: "+224"
        }, {
            f: "🇬🇼",
            n: "Guinea-Bissau",
            c: "+245"
        },
        {
            f: "🇬🇾",
            n: "Guyana",
            c: "+592"
        }, {
            f: "🇭🇹",
            n: "Haiti",
            c: "+509"
        }, {
            f: "🇭🇳",
            n: "Honduras",
            c: "+504"
        },
        {
            f: "🇭🇺",
            n: "Hungary",
            c: "+36"
        }, {
            f: "🇮🇸",
            n: "Iceland",
            c: "+354"
        }, {
            f: "🇮🇳",
            n: "India",
            c: "+91"
        },
        {
            f: "🇮🇩",
            n: "Indonesia",
            c: "+62"
        }, {
            f: "🇮🇷",
            n: "Iran",
            c: "+98"
        }, {
            f: "🇮🇶",
            n: "Iraq",
            c: "+964"
        },
        {
            f: "🇮🇪",
            n: "Ireland",
            c: "+353"
        }, {
            f: "🇮🇱",
            n: "Israel",
            c: "+972"
        }, {
            f: "🇮🇹",
            n: "Italy",
            c: "+39"
        },
        {
            f: "🇯🇲",
            n: "Jamaica",
            c: "+1-876"
        }, {
            f: "🇯🇵",
            n: "Japan",
            c: "+81"
        }, {
            f: "🇯🇴",
            n: "Jordan",
            c: "+962"
        },
        {
            f: "🇰🇿",
            n: "Kazakhstan",
            c: "+7"
        }, {
            f: "🇰🇪",
            n: "Kenya",
            c: "+254"
        }, {
            f: "🇰🇼",
            n: "Kuwait",
            c: "+965"
        },
        {
            f: "🇰🇬",
            n: "Kyrgyzstan",
            c: "+996"
        }, {
            f: "🇱🇦",
            n: "Laos",
            c: "+856"
        }, {
            f: "🇱🇻",
            n: "Latvia",
            c: "+371"
        },
        {
            f: "🇱🇧",
            n: "Lebanon",
            c: "+961"
        }, {
            f: "🇱🇸",
            n: "Lesotho",
            c: "+266"
        }, {
            f: "🇱🇷",
            n: "Liberia",
            c: "+231"
        },
        {
            f: "🇱🇾",
            n: "Libya",
            c: "+218"
        }, {
            f: "🇱🇮",
            n: "Liechtenstein",
            c: "+423"
        }, {
            f: "🇱🇹",
            n: "Lithuania",
            c: "+370"
        },
        {
            f: "🇱🇺",
            n: "Luxembourg",
            c: "+352"
        }, {
            f: "🇲🇬",
            n: "Madagascar",
            c: "+261"
        }, {
            f: "🇲🇼",
            n: "Malawi",
            c: "+265"
        },
        {
            f: "🇲🇾",
            n: "Malaysia",
            c: "+60"
        }, {
            f: "🇲🇻",
            n: "Maldives",
            c: "+960"
        }, {
            f: "🇲🇱",
            n: "Mali",
            c: "+223"
        },
        {
            f: "🇲🇹",
            n: "Malta",
            c: "+356"
        }, {
            f: "🇲🇷",
            n: "Mauritania",
            c: "+222"
        }, {
            f: "🇲🇺",
            n: "Mauritius",
            c: "+230"
        },
        {
            f: "🇲🇽",
            n: "Mexico",
            c: "+52"
        }, {
            f: "🇲🇩",
            n: "Moldova",
            c: "+373"
        }, {
            f: "🇲🇨",
            n: "Monaco",
            c: "+377"
        },
        {
            f: "🇲🇳",
            n: "Mongolia",
            c: "+976"
        }, {
            f: "🇲🇪",
            n: "Montenegro",
            c: "+382"
        }, {
            f: "🇲🇦",
            n: "Morocco",
            c: "+212"
        },
        {
            f: "🇲🇿",
            n: "Mozambique",
            c: "+258"
        }, {
            f: "🇲🇲",
            n: "Myanmar",
            c: "+95"
        }, {
            f: "🇳🇦",
            n: "Namibia",
            c: "+264"
        },
        {
            f: "🇳🇵",
            n: "Nepal",
            c: "+977"
        }, {
            f: "🇳🇱",
            n: "Netherlands",
            c: "+31"
        }, {
            f: "🇳🇿",
            n: "New Zealand",
            c: "+64"
        },
        {
            f: "🇳🇮",
            n: "Nicaragua",
            c: "+505"
        }, {
            f: "🇳🇪",
            n: "Niger",
            c: "+227"
        }, {
            f: "🇳🇬",
            n: "Nigeria",
            c: "+234"
        },
        {
            f: "🇳🇴",
            n: "Norway",
            c: "+47"
        }, {
            f: "🇴🇲",
            n: "Oman",
            c: "+968"
        }, {
            f: "🇵🇰",
            n: "Pakistan",
            c: "+92"
        },
        {
            f: "🇵🇦",
            n: "Panama",
            c: "+507"
        }, {
            f: "🇵🇬",
            n: "Papua New Guinea",
            c: "+675"
        }, {
            f: "🇵🇾",
            n: "Paraguay",
            c: "+595"
        },
        {
            f: "🇵🇪",
            n: "Peru",
            c: "+51"
        }, {
            f: "🇵🇭",
            n: "Philippines",
            c: "+63"
        }, {
            f: "🇵🇱",
            n: "Poland",
            c: "+48"
        },
        {
            f: "🇵🇹",
            n: "Portugal",
            c: "+351"
        }, {
            f: "🇶🇦",
            n: "Qatar",
            c: "+974"
        }, {
            f: "🇷🇴",
            n: "Romania",
            c: "+40"
        },
        {
            f: "🇷🇺",
            n: "Russia",
            c: "+7"
        }, {
            f: "🇷🇼",
            n: "Rwanda",
            c: "+250"
        }, {
            f: "🇸🇦",
            n: "Saudi Arabia",
            c: "+966"
        },
        {
            f: "🇸🇳",
            n: "Senegal",
            c: "+221"
        }, {
            f: "🇷🇸",
            n: "Serbia",
            c: "+381"
        }, {
            f: "🇸🇱",
            n: "Sierra Leone",
            c: "+232"
        },
        {
            f: "🇸🇬",
            n: "Singapore",
            c: "+65"
        }, {
            f: "🇸🇰",
            n: "Slovakia",
            c: "+421"
        }, {
            f: "🇸🇮",
            n: "Slovenia",
            c: "+386"
        },
        {
            f: "🇸🇴",
            n: "Somalia",
            c: "+252"
        }, {
            f: "🇿🇦",
            n: "South Africa",
            c: "+27"
        }, {
            f: "🇸🇸",
            n: "South Sudan",
            c: "+211"
        },
        {
            f: "🇪🇸",
            n: "Spain",
            c: "+34"
        }, {
            f: "🇱🇰",
            n: "Sri Lanka",
            c: "+94"
        }, {
            f: "🇸🇩",
            n: "Sudan",
            c: "+249"
        },
        {
            f: "🇸🇷",
            n: "Suriname",
            c: "+597"
        }, {
            f: "🇸🇪",
            n: "Sweden",
            c: "+46"
        }, {
            f: "🇨🇭",
            n: "Switzerland",
            c: "+41"
        },
        {
            f: "🇸🇾",
            n: "Syria",
            c: "+963"
        }, {
            f: "🇹🇼",
            n: "Taiwan",
            c: "+886"
        }, {
            f: "🇹🇯",
            n: "Tajikistan",
            c: "+992"
        },
        {
            f: "🇹🇿",
            n: "Tanzania",
            c: "+255"
        }, {
            f: "🇹🇭",
            n: "Thailand",
            c: "+66"
        }, {
            f: "🇹🇱",
            n: "Timor-Leste",
            c: "+670"
        },
        {
            f: "🇹🇬",
            n: "Togo",
            c: "+228"
        }, {
            f: "🇹🇹",
            n: "Trinidad & Tobago",
            c: "+1-868"
        }, {
            f: "🇹🇳",
            n: "Tunisia",
            c: "+216"
        },
        {
            f: "🇹🇷",
            n: "Turkey",
            c: "+90"
        }, {
            f: "🇹🇲",
            n: "Turkmenistan",
            c: "+993"
        }, {
            f: "🇺🇬",
            n: "Uganda",
            c: "+256"
        },
        {
            f: "🇺🇦",
            n: "Ukraine",
            c: "+380"
        }, {
            f: "🇦🇪",
            n: "UAE",
            c: "+971"
        }, {
            f: "🇬🇧",
            n: "United Kingdom",
            c: "+44"
        },
        {
            f: "🇺🇸",
            n: "USA",
            c: "+1"
        }, {
            f: "🇺🇾",
            n: "Uruguay",
            c: "+598"
        }, {
            f: "🇺🇿",
            n: "Uzbekistan",
            c: "+998"
        },
        {
            f: "🇻🇪",
            n: "Venezuela",
            c: "+58"
        }, {
            f: "🇻🇳",
            n: "Vietnam",
            c: "+84"
        }, {
            f: "🇾🇪",
            n: "Yemen",
            c: "+967"
        },
        {
            f: "🇿🇲",
            n: "Zambia",
            c: "+260"
        }, {
            f: "🇿🇼",
            n: "Zimbabwe",
            c: "+263"
        }
    ];
    const INDIA_IDX = COUNTRIES.findIndex(c => c.n === "India");

    function buildCountrySel() {
        const sel = document.createElement('select');
        sel.className = 'country-sel';
        COUNTRIES.forEach((c, i) => {
            const opt = document.createElement('option');
            opt.value = i;
            opt.textContent = c.f + ' ' + c.c;
            opt.title = c.n;
            sel.appendChild(opt);
        });
        sel.value = INDIA_IDX >= 0 ? INDIA_IDX : 0;
        return sel;
    }

    function addPhoneRow(listId) {
        const list = document.getElementById(listId);
        const isFirst = list.children.length === 0;

        const row = document.createElement('div');
        row.className = 'multi-row';

        const wrap = document.createElement('div');
        wrap.className = 'phone-wrap form-inp';
        wrap.style.padding = '0';
        wrap.style.display = 'flex';
        wrap.appendChild(buildCountrySel());

        const inp = document.createElement('input');
        inp.type = 'tel';
        inp.className = 'phone-num-inp';
        inp.placeholder = 'XXXXX XXXXX';
        wrap.appendChild(inp);
        row.appendChild(wrap);

        if (!isFirst) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'row-remove-btn';
            btn.innerHTML = '<i class="bi bi-x-lg"></i>';
            btn.onclick = () => row.remove();
            row.appendChild(btn);
        }
        list.appendChild(row);
    }

    function addEmailRow(listId) {
        const list = document.getElementById(listId);
        const isFirst = list.children.length === 0;

        const row = document.createElement('div');
        row.className = 'multi-row';

        const inp = document.createElement('input');
        inp.type = 'email';
        inp.className = 'form-inp multi-email-inp';
        inp.placeholder = 'email@company.com';
        row.appendChild(inp);

        if (!isFirst) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'row-remove-btn';
            btn.innerHTML = '<i class="bi bi-x-lg"></i>';
            btn.onclick = () => row.remove();
            row.appendChild(btn);
        }
        list.appendChild(row);
    }

    /* Auto-init first rows when modals are opened */
    function initModalRows(modalId) {
        const prefixes = {
            addLeadModal: 'add',
            editLeadModal: 'edit'
        };
        const p = prefixes[modalId];
        if (!p) return;
        const el = document.getElementById(p + '-email-list');
        const pl = document.getElementById(p + '-phone-list');
        if (el && el.children.length === 0) addEmailRow(p + '-email-list');
        if (pl && pl.children.length === 0) addPhoneRow(p + '-phone-list');
    }

    /* Hook into your existing openModal if present, otherwise patch here */
    const _origOpenModal = typeof openModal === 'function' ? openModal : null;

    function openModal(id) {
        if (_origOpenModal) _origOpenModal(id);
        initModalRows(id);
    }

    /* Also init on page load in case modals start visible */
    document.addEventListener('DOMContentLoaded', () => {
        ['addLeadModal', 'editLeadModal'].forEach(id => {
            const m = document.getElementById(id);
            if (m && getComputedStyle(m).display !== 'none') initModalRows(id);
        });
    });
</script>

@endsection