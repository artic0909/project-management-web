@extends('admin.layout.app')

@section('title', 'Add Sources')

@section('content')

<!-- ═══ PAGE CONTENT AREA ═══ -->
<main class="page-area" id="pageArea">

    <div class="page" id="page-dashboard">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Your All Sources</h1>
            </div>
        </div>


        <!-- MAIN GRID -->
        <div class="dash-grid">


            <!-- Source Table -->
            <div class="dash-card span-12">
                <div class="card-head">
                    <div>
                        <div class="card-title">Sources</div>
                        <div class="card-sub">3 total</div>
                    </div>
                    <div class="card-actions">
                        <button class="btn-primary-solid sm" onclick="openModal('addModal')"><i class="bi bi-plus-lg"></i> Add Source</button>
                    </div>
                </div>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Source Name</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><span class="src-tag website">Linkedin</span></td>
                                <td><strong style="color:#10b981">Admin</strong></td>
                                <td>
                                    <!-- Modal Btns -->
                                    <div class="row-actions">
                                        <button class="ra-btn" onclick="openModal('editModal')"><i class="bi bi-pencil-fill"></i></button>
                                        <button class="ra-btn danger" onclick="openModal('deleteModal')"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="table-footer">
                    <span class="tf-info">Showing 5 of 24 Sources</span>
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
    <div class="modal-backdrop" id="addModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd"><span>Add Source</span><button class="modal-close" onclick="closeModal('addModal')"><i class="bi bi-x-lg"></i></button></div>
            <div class="modal-bd">

                <div class="form-row"><label class="form-lbl">Source Name *</label><input type="text" class="form-inp" placeholder="Source name"></div>

            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('addModal')">Cancel</button>
                <button class="btn-primary-solid" onclick="closeModal('addModal');showToast('success','Source Added!','bi-person-check-fill')">
                    <i class="bi bi-plus-lg"></i> Add Source
                </button>
            </div>
        </div>
    </div>


    <!-- Edit Modal -->
    <div class="modal-backdrop" id="editModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd"><span>Update Source</span><button class="modal-close" onclick="closeModal('editModal')"><i class="bi bi-x-lg"></i></button></div>
            <div class="modal-bd">

                <div class="form-row"><label class="form-lbl">Source Name *</label><input type="text" class="form-inp" placeholder="Source name"></div>

            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('editModal')">Cancel</button>
                <button class="btn-primary-solid" onclick="closeModal('editModal');showToast('success','Source Updated!','bi-person-check-fill')">
                    <i class="bi bi-plus-lg"></i> Update Source
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Delete Source</span>
                <button class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:#111827;">Are you sure?</h3>
                <p style="margin:0;font-size:14px;color:#6b7280;line-height:1.6;">Are you sure you want to delete this Source?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                <button style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;" onclick="closeModal('deleteModal');showToast('success','Source Deleted!','bi-trash3-fill')">
                    <i class="bi bi-trash3-fill"></i> Delete Source
                </button>
            </div>
        </div>
    </div>

</main>

@endsection