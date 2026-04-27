@extends('admin.layout.app')

@section('title', 'Support Tickets')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page">
        <div class="page-header">
            <div>
                <h1 class="page-title">Support Tickets</h1>
                <p class="page-desc">Manage all customer support requests and ticket statuses.</p>
            </div>
        </div>

        <div class="dash-grid">
            <div class="dash-card span-12">
                <div class="card-head">
                    <div>
                        <div class="card-title">All Tickets</div>
                        <div class="card-sub">Showing {{ $tickets->count() }} of {{ $tickets->total() }} records</div>
                    </div>
                    
                    <div class="card-actions mb-2">
                        <form action="{{ route('admin.supports.index') }}" method="GET" class="card-actions mb-0">
                            <div class="global-search">
                                <i class="bi bi-search"></i>
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search company, name, subject..." onkeypress="if(event.key === 'Enter') this.form.submit()">
                            </div>

                            <select name="status" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                                <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>

                            <select name="priority" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Priority</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>SL.</th>
                                <th>Date</th>
                                <th>Company & User</th>
                                <th>Subject</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                            <tr>
                                <td style="color:var(--t4);font-size:12px;font-weight:600;">{{ $loop->iteration + ($tickets->currentPage() - 1) * $tickets->perPage() }}</td>
                                <td>
                                    <div class="ls" style="font-size:12px; font-weight:600;">{{ $ticket->created_at->format('d M Y') }}</div>
                                    <div class="ls" style="font-size:10px;">{{ $ticket->created_at->format('h:i A') }}</div>
                                </td>
                                <td>
                                    <div class="ln">{{ $ticket->company_name }}</div>
                                    <div class="ls">{{ $ticket->your_name }}</div>
                                </td>
                                <td>
                                    <div class="ln">{{ $ticket->subject }}</div>
                                    <div class="ls">{{ $ticket->domain_name ?? 'No Domain' }}</div>
                                </td>
                                <td>
                                    @php
                                        $pClr = ['high' => '#ef4444', 'medium' => '#f59e0b', 'low' => '#0ea5e9'];
                                        $pclr = $pClr[$ticket->priority] ?? '#6366f1';
                                    @endphp
                                    <span class="status-pill" style="background:{{ $pclr }}15; color:{{ $pclr }}; border-radius: 4px; font-weight: 700; text-transform: uppercase; font-size: 10px; padding: 2px 8px;">
                                        {{ $ticket->priority }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $sClr = ['pending' => '#f59e0b', 'review' => '#0ea5e9', 'replied' => '#10b981', 'closed' => '#6b7280'];
                                        $sclr = $sClr[$ticket->status] ?? '#6366f1';
                                    @endphp
                                    <span class="status-pill" style="background:{{ $sclr }}15; color:{{ $sclr }};">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <a href="tel:{{ $ticket->phone }}" class="ra-btn phone" title="Call: {{ $ticket->phone }}">
                                            <i class="bi bi-telephone-fill"></i>
                                        </a>
                                        <a href="mailto:{{ $ticket->email }}" class="ra-btn email" title="Email: {{ $ticket->email }}">
                                            <i class="bi bi-envelope-fill"></i>
                                        </a>

                                        <a href="{{ route('admin.supports.show', $ticket->id) }}" class="ra-btn" title="View & Reply"><i class="bi bi-eye-fill"></i></a>
                                        <button class="ra-btn danger" title="Delete" onclick="confirmDelete('{{ route('admin.supports.destroy', $ticket->id) }}')"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding: 40px; color: var(--t4);">No support tickets found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer" style="padding:16px 20px; border-top:1px solid var(--b2); display:flex; justify-content:space-between; align-items:center;">
                    <span class="tf-info" style="font-size:13px; color:var(--t3); font-weight:500;">Showing {{ $tickets->count() }} of {{ $tickets->total() }} Tickets</span>
                    <div class="tf-pagination">
                        {{ $tickets->links('admin.includes.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Delete Ticket</span>
                <button class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:var(--t1);">Are you sure?</h3>
                <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">Are you sure you want to delete this support ticket?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                <form id="deleteForm" method="POST" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-trash3-fill"></i> Delete Ticket
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    function confirmDelete(url) {
        const form = document.getElementById('deleteForm');
        form.action = url;
        openModal('deleteModal');
    }

    // Modal Helpers
    function openModal(id) {
        document.getElementById(id).classList.add('open');
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
    }
</script>

@endsection
