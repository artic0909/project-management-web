@extends('admin.layout.app')

@section('title', 'Status — Leads, Orders, Payments, Projects')

@section('content')

{{-- Flash Toasts --}}
@if(session('success'))
<script>document.addEventListener('DOMContentLoaded',()=>showToast('success','{{ session('success') }}','bi-check-circle-fill'));</script>
@endif
@if(session('error'))
<script>document.addEventListener('DOMContentLoaded',()=>showToast('error','{{ session('error') }}','bi-x-circle-fill'));</script>
@endif
@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded',()=>{
    showToast('error','{{ $errors->first() }}','bi-exclamation-triangle-fill');
    @if(session('open_modal')) openModal('{{ session('open_modal') }}'); @endif
});
</script>
@endif

<main class="page-area" id="pageArea">
    <div class="page" id="page-status">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Status Manager</h1>
                <p class="page-desc">Manage statuses for Leads, Orders, Payments & Projects</p>
            </div>
            <button class="btn-primary-solid sm" onclick="openModal('addStatusModal')">
                <i class="bi bi-plus-lg"></i> Add Status
            </button>
        </div>

        <!-- KPI CARDS -->
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">

            @php
                $kpis = [
                    ['label'=>'Lead',    'count'=>$leadStatusCount,    'icon'=>'bi-person-lines-fill', 'color'=>'#6366f1', 'rgb'=>'99,102,241',  'pct'=> $statusCount ? round($leadStatusCount/$statusCount*100) : 0],
                    ['label'=>'Order',   'count'=>$orderStatusCount,   'icon'=>'bi-bag-check-fill',    'color'=>'#10b981', 'rgb'=>'16,185,129',  'pct'=> $statusCount ? round($orderStatusCount/$statusCount*100) : 0],
                    ['label'=>'Payment', 'count'=>$paymentStatusCount, 'icon'=>'bi-credit-card-2-front-fill','color'=>'#f59e0b','rgb'=>'245,158,11','pct'=> $statusCount ? round($paymentStatusCount/$statusCount*100) : 0],
                    ['label'=>'Project', 'count'=>$projectStatusCount, 'icon'=>'bi-kanban-fill',        'color'=>'#8b5cf6', 'rgb'=>'139,92,246', 'pct'=> $statusCount ? round($projectStatusCount/$statusCount*100) : 0],
                ];
            @endphp

            @foreach($kpis as $kpi)
            <div class="dash-card" style="padding:18px 20px;cursor:pointer;transition:var(--transition);"
                onmouseover="this.style.borderColor='{{ $kpi['color'] }}'"
                onmouseout="this.style.borderColor='var(--b1)'">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                    <div style="width:42px;height:42px;border-radius:11px;background:rgba({{ $kpi['rgb'] }},.14);display:flex;align-items:center;justify-content:center;">
                        <i class="bi {{ $kpi['icon'] }}" style="font-size:19px;color:{{ $kpi['color'] }};"></i>
                    </div>
                    <span style="font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px;background:rgba({{ $kpi['rgb'] }},.12);color:{{ $kpi['color'] }};">{{ $kpi['label'] }}</span>
                </div>
                <div style="font-size:28px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">{{ $kpi['count'] }}</div>
                <div style="font-size:12px;color:var(--t3);font-weight:500;margin-top:5px;">Total {{ $kpi['label'] }} Statuses</div>
                <div style="margin-top:12px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:{{ $kpi['pct'] }}%;background:{{ $kpi['color'] }};border-radius:3px;transition:width .6s ease;"></div>
                </div>
            </div>
            @endforeach

        </div>

        <!-- STATUS TABLE CARD -->
        <div class="dash-grid">
            <div class="dash-card span-12">
                <div class="card-head" style="padding:16px 18px 0;">
                    <div>
                        <div class="card-title">All Statuses</div>
                        <div class="card-sub" id="tableSubCount">{{ $statusCount }} total statuses</div>
                    </div>
                    <div class="card-actions mb-2">
                        <select class="filter-select" id="typeFilter" onchange="filterTable()">
                            <option value="all">All Types</option>
                            <option value="lead">Lead</option>
                            <option value="order">Order</option>
                            <option value="payment">Payment</option>
                            <option value="project">Project</option>
                        </select>
                        <div class="global-search" style="max-width:220px;">
                            <i class="bi bi-search"></i>
                            <input type="text" id="statusSearch" placeholder="Search status…" oninput="filterTable()">
                        </div>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table" id="statusTable">
                        <thead>
                            <tr>
                                <th style="width:52px;">SL</th>
                                <th>Status Name</th>
                                <th>Type</th>
                                <th style="width:110px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="statusTbody">
                            @forelse($statuses as $status)
                            <tr data-type="{{ $status->type }}">
                                <td>{{ $loop->iteration }}</td>
                                <td><span style="font-weight:600;color:var(--t1);">{{ $status->name }}</span></td>
                                <td><span class="type-badge {{ $status->type }}">{{ ucfirst($status->type) }}</span></td>
                                <td>
                                    <div class="row-actions">
                                        <button class="ra-btn" onclick="openEditModal({{ $status->id }},'{{ addslashes($status->name) }}','{{ $status->type }}')" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button class="ra-btn danger" onclick="openDeleteModal({{ $status->id }},'{{ addslashes($status->name) }}')" title="Delete">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr id="noDataRow">
                                <td colspan="4" class="text-center">No statuses found. Add your first one!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Empty state (for JS filter) -->
                <div id="emptyState" style="display:none;padding:48px 24px;text-align:center;">
                    <div style="width:56px;height:56px;background:var(--bg4);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                        <i class="bi bi-search" style="font-size:22px;color:var(--t3);"></i>
                    </div>
                    <div style="font-size:15px;font-weight:700;color:var(--t2);margin-bottom:5px;">No statuses found</div>
                    <div style="font-size:13px;color:var(--t3);">Try a different filter or search term</div>
                </div>

                <div class="table-footer">
                    <span class="tf-info" id="visibleCount">Showing {{ $statusCount }} of {{ $statusCount }} statuses</span>
                    <div class="tf-pagination">
                        <button class="pg-btn"><i class="bi bi-chevron-left"></i></button>
                        <button class="pg-btn active">1</button>
                        <button class="pg-btn"><i class="bi bi-chevron-right"></i></button>
                    </div>
                    <div class="tf-per-page"></div>
                </div>
            </div>
        </div>

    </div><!-- end page -->


    {{-- ══════════════════════════════════
         ADD STATUS MODAL
    ══════════════════════════════════ --}}
    <div class="modal-backdrop" id="addStatusModal" onclick="closeModal('addStatusModal')">
        <div class="modal-box sm-box" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <span>Add New Status</span>
                <button class="modal-close" onclick="closeModal('addStatusModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="{{ route($routePrefix . '.status.store') }}" method="POST">
                @csrf
                <div class="modal-bd">
                    <div class="form-row">
                        <label class="form-lbl">Status Name *</label>
                        <input type="text" name="name" class="form-inp" placeholder="e.g. Under Review"
                            value="{{ old('name') }}" required>
                        @error('name')<span style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Type *</label>
                        <select name="type" class="form-inp" required>
                            <option value="">— Select Type —</option>
                            @foreach(['lead','order','payment','project'] as $t)
                            <option value="{{ $t }}" {{ old('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                        @error('type')<span style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="modal-ft">
                    <button type="button" class="btn-ghost" onclick="closeModal('addStatusModal')">Cancel</button>
                    <button type="submit" class="btn-primary-solid">
                        <i class="bi bi-plus-lg"></i> Add Status
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ══════════════════════════════════
         EDIT STATUS MODAL
    ══════════════════════════════════ --}}
    <div class="modal-backdrop" id="editStatusModal" onclick="closeModal('editStatusModal')">
        <div class="modal-box sm-box" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <span>Edit Status</span>
                <button class="modal-close" onclick="closeModal('editStatusModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <form id="editStatusForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-bd">
                    <div class="form-row">
                        <label class="form-lbl">Status Name *</label>
                        <input type="text" name="name" id="editStatusName" class="form-inp" placeholder="Status name" required>
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Type *</label>
                        <select name="type" id="editStatusType" class="form-inp" required>
                            @foreach(['lead','order','payment','project'] as $t)
                            <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-ft">
                    <button type="button" class="btn-ghost" onclick="closeModal('editStatusModal')">Cancel</button>
                    <button type="submit" class="btn-primary-solid">
                        <i class="bi bi-pencil-fill"></i> Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ══════════════════════════════════
         DELETE STATUS MODAL
    ══════════════════════════════════ --}}
    <div class="modal-backdrop" id="deleteStatusModal" onclick="closeModal('deleteStatusModal')">
        <div class="modal-box sm-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Delete Status</span>
                <button class="modal-close" onclick="closeModal('deleteStatusModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <form id="deleteStatusForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                    <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                    </div>
                    <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:var(--t1);">Are you sure?</h3>
                    <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">
                        You are about to delete <strong id="deleteStatusName" style="color:#dc2626;"></strong>.<br>
                        This action <strong style="color:#dc2626;">cannot be undone.</strong>
                    </p>
                </div>
                <div class="modal-ft" style="border-top:1px solid #fecaca;">
                    <button type="button" class="btn-ghost" onclick="closeModal('deleteStatusModal')">Cancel</button>
                    <button type="submit" style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-trash3-fill"></i> Delete
                    </button>
                </div>
            </form>
        </div>
    </div>


    <style>
        .type-badge { display:inline-flex;align-items:center;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;text-transform:capitalize;letter-spacing:.02em; }
        .type-badge.lead    { background:rgba(99,102,241,.12);color:#818cf8; }
        .type-badge.order   { background:rgba(16,185,129,.12);color:#10b981; }
        .type-badge.payment { background:rgba(245,158,11,.12);color:#f59e0b; }
        .type-badge.project { background:rgba(139,92,246,.12);color:#8b5cf6; }
        tr.hidden-row { display:none; }
    </style>

    <script>
        // ── Filter + Search ──
        function filterTable() {
            const type   = document.getElementById('typeFilter').value.toLowerCase();
            const search = document.getElementById('statusSearch').value.toLowerCase();
            const rows   = document.querySelectorAll('#statusTbody tr[data-type]');
            let visible  = 0;

            rows.forEach(row => {
                const typeOk = (type === 'all' || row.dataset.type === type);
                const textOk = row.textContent.toLowerCase().includes(search);
                if (typeOk && textOk) { row.classList.remove('hidden-row'); visible++; }
                else                  { row.classList.add('hidden-row'); }
            });

            document.getElementById('visibleCount').textContent = `Showing ${visible} of ${rows.length} statuses`;
            document.getElementById('emptyState').style.display = visible === 0 ? 'block' : 'none';
        }

        // ── Edit Modal ──
        function openEditModal(id, name, type) {
            document.getElementById('editStatusForm').action = `{{ url($routePrefix . '/add-status') }}/${id}`;
            document.getElementById('editStatusName').value  = name;
            document.getElementById('editStatusType').value  = type;
            openModal('editStatusModal');
        }

        // ── Delete Modal ──
        function openDeleteModal(id, name) {
            document.getElementById('deleteStatusForm').action  = `{{ url($routePrefix . '/add-status') }}/${id}`;
            document.getElementById('deleteStatusName').textContent = name;
            openModal('deleteStatusModal');
        }

        // ── Re-open modal on validation error ──
        @if(session('open_modal'))
            document.addEventListener('DOMContentLoaded', () => openModal('{{ session('open_modal') }}'));
        @endif
    </script>

</main>

@endsection