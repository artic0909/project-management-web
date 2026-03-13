@extends('admin.layout.app')

@section('title', 'Add Sales Persons')

@section('content')

<!-- ═══ PAGE CONTENT AREA ═══ -->
<main class="page-area" id="pageArea">

    <div class="page" id="page-dashboard">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Your All Sales Persons</h1>
            </div>
        </div>


        <!-- MAIN GRID -->
        <div class="dash-grid">


            <!-- Sales Person Table -->
            <div class="dash-card span-12">
                <div class="card-head">
                    <div>
                        <div class="card-title">Sales Persons</div>
                        <div class="card-sub">147 total</div>
                    </div>
                    <div class="card-actions">
                        <button class="btn-primary-solid sm" onclick="openModal('addSalesPersonModal')"><i class="bi bi-plus-lg"></i> Add Sales Person</button>
                    </div>
                </div>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created By</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <div class="lead-cell">
                                        <div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">TC</div>
                                        <div>
                                            <div class="ln">TechCorp Solutions</div>
                                            <div class="ls">techcorp@example.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="src-tag website">techcorp@example.com</span></td>
                                <td><strong style="color:#10b981">Admin</strong></td>
                                <td><span class="lead-stage hot">Working</span></td>
                                <td>
                                    <!-- Modal Btns -->
                                    <div class="row-actions">
                                        <button class="ra-btn"><i class="bi bi-power"></i></button>
                                        <button class="ra-btn" onclick="openModal('editSalesPersonModal')"><i class="bi bi-pencil-fill"></i></button>
                                        <button class="ra-btn"><i class="bi bi-envelope-fill"></i></button>
                                        <button class="ra-btn danger" onclick="openModal('deleteSalesPersonModal')"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="table-footer">
                    <span class="tf-info">Showing 5 of 24 Sales Persons</span>
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
    <div class="modal-backdrop" id="addSalesPersonModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd"><span>Add Sales Person</span><button class="modal-close" onclick="closeModal('addSalesPersonModal')"><i class="bi bi-x-lg"></i></button></div>
            <div class="modal-bd">
                <div class="form-grid">
                    <div class="form-row"><label class="form-lbl">Sales Person *</label><input type="text" class="form-inp" placeholder="Sales Person name"></div>
                    <div class="form-row"><label class="form-lbl">Email *</label><input type="email" class="form-inp" placeholder="email@company.com"></div>
                    <div class="form-row"><label class="form-lbl">Set Password *</label><input type="text" class="form-inp" value="12345"></div>
                    <div class="form-row"><label class="form-lbl">Confirm Password *</label><input type="text" class="form-inp" value="12345"></div>
                </div>
            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('addSalesPersonModal')">Cancel</button>
                <button class="btn-primary-solid" onclick="closeModal('addSalesPersonModal');showToast('success','Sales Person Added!','bi-person-check-fill')">
                    <i class="bi bi-plus-lg"></i> Add Sales Person
                </button>
            </div>
        </div>
    </div>


    <!-- Edit Modal -->
    <div class="modal-backdrop" id="editSalesPersonModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd"><span>Update Sales Person</span><button class="modal-close" onclick="closeModal('editSalesPersonModal')"><i class="bi bi-x-lg"></i></button></div>
            <div class="modal-bd">
                <div class="form-grid">
                    <div class="form-row"><label class="form-lbl">Sales Person *</label><input type="text" class="form-inp" placeholder="Sales Person name"></div>
                    <div class="form-row"><label class="form-lbl">Email *</label><input type="email" class="form-inp" placeholder="email@company.com"></div>
                    <div class="form-row"><label class="form-lbl">Set Password *</label><input type="text" class="form-inp" value="12345"></div>
                    <div class="form-row"><label class="form-lbl">Confirm Password *</label><input type="text" class="form-inp" value="12345"></div>
                </div>
            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('editSalesPersonModal')">Cancel</button>
                <button class="btn-primary-solid" onclick="closeModal('editSalesPersonModal');showToast('success','Sales Person Updated!','bi-person-check-fill')">
                    <i class="bi bi-plus-lg"></i> Update Sales Person
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal-backdrop" id="deleteSalesPersonModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Delete Sales Person</span>
                <button class="modal-close" onclick="closeModal('deleteSalesPersonModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:#111827;">Are you sure?</h3>
                <p style="margin:0;font-size:14px;color:#6b7280;line-height:1.6;">Are you sure you want to delete this sales person?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('deleteSalesPersonModal')">Cancel</button>
                <button style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;" onclick="closeModal('deleteSalesPersonModal');showToast('success','Sales Person Deleted!','bi-trash3-fill')">
                    <i class="bi bi-trash3-fill"></i> Delete Sales Person
                </button>
            </div>
        </div>
    </div>

</main>


@endsection