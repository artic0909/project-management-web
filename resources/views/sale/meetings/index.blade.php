@extends('admin.layout.app')

@section('title', 'My Meetings')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-meetings-sale">
        
        <div class="page-header" style="margin-bottom:24px;">
            <div>
                <h1 class="page-title">My Meeting Schedule</h1>
                <p class="page-desc">Stay updated on your upcoming discussions and briefings.</p>
            </div>
            <div>
                <a href="{{ route('sale.meetings.create') }}" class="btn-primary-solid">
                    <i class="bi bi-plus-lg"></i> Schedule Meeting
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="padding:14px 18px;background:rgba(16,185,129,.1);color:#10b981;border-radius:var(--r);border:1px solid rgba(16,185,129,.2);display:flex;align-items:center;gap:10px;margin-bottom:20px;">
                <i class="bi bi-check-circle-fill"></i>
                <span style="font-size:14px;font-weight:600;">{{ session('success') }}</span>
            </div>
        @endif

        <div class="dash-card">
            <div class="card-head" style="padding:16px 20px;">
                <div class="card-title">Assigned Timeline</div>
            </div>
            <div class="card-body" style="padding:0;">
                <div class="meeting-list">
                    @forelse($meetings as $meeting)
                        <div class="meeting-item {{ strtolower($meeting->status) }}">
                            <div class="m-date">
                                <span class="d">{{ $meeting->meeting_date->format('d') }}</span>
                                <span class="m">{{ $meeting->meeting_date->format('M') }}</span>
                            </div>
                            <div class="m-info">
                                <div class="m-top">
                                    <span class="m-type-tag {{ strtolower($meeting->meeting_type) }}">{{ strtoupper($meeting->meeting_type) }}</span>
                                    <span class="m-time"><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}</span>
                                    <span class="m-status-pill {{ strtolower($meeting->status) }}">{{ $meeting->status }}</span>
                                </div>
                                <h3 class="m-title">{{ $meeting->meeting_title }}</h3>
                                
                                <div class="m-links">
                                    @if($meeting->meeting_type == 'lead' && $meeting->lead)
                                        <a href="{{ route('sale.leads.show', $meeting->lead_id) }}" class="m-link"><i class="bi bi-person-circle"></i> {{ $meeting->lead->company }}</a>
                                    @elseif($meeting->meeting_type == 'order' && $meeting->order)
                                        <a href="{{ route('sale.orders.index') }}" class="m-link"><i class="bi bi-bag-check"></i> Order #{{ $meeting->order->id }}</a>
                                    @endif

                                    @if($meeting->meeting_link)
                                        <a href="{{ $meeting->meeting_link }}" target="_blank" class="m-link active"><i class="bi bi-camera-video"></i> Join Now</a>
                                    @endif
                                </div>
                            </div>
                            <div class="m-footer">
                                <div class="m-actions">
                                    <a href="{{ route('sale.meetings.show', $meeting->id) }}" class="act-btn"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('sale.meetings.edit', $meeting->id) }}" class="act-btn"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('sale.meetings.destroy', $meeting->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Archive this meeting?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="act-btn danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="padding:40px; text-align:center; color:var(--t4);">
                            No meetings assigned to you yet.
                        </div>
                    @endforelse
                </div>
                <div style="padding:20px;">
                    {{ $meetings->links() }}
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    /* Reuse styles from admin index for consistency */
    .meeting-list { display: flex; flex-direction: column; }
    .meeting-item { display: grid; grid-template-columns: 80px 1fr 120px; gap: 20px; padding: 20px; border-bottom: 1px solid var(--b1); transition: 0.2s; }
    .meeting-item:hover { background: var(--bg3); }
    .m-date { display: flex; flex-direction: column; align-items: center; justify-content: center; background: var(--bg4); border-radius: 12px; height: 70px; width: 70px; border: 1px solid var(--b1); }
    .m-date .d { font-size: 20px; font-weight: 800; color: var(--t1); line-height: 1; }
    .m-date .m { font-size: 10px; font-weight: 700; color: var(--t3); text-transform: uppercase; }
    .m-info { display: flex; flex-direction: column; gap: 4px; }
    .m-top { display: flex; align-items: center; gap: 10px; }
    .m-type-tag { font-size: 9px; font-weight: 800; padding: 2px 8px; border-radius: 20px; }
    .m-type-tag.lead { background: rgba(99,102,241,0.12); color: #6366f1; }
    .m-type-tag.order { background: rgba(245,158,11,0.12); color: #f59e0b; }
    .m-time { font-size: 11px; font-weight: 700; color: var(--t2); }
    .m-status-pill { font-size: 9px; font-weight: 700; padding: 2px 8px; border-radius: 4px; text-transform: capitalize; }
    .m-status-pill.pending { background: #fef3c7; color: #92400e; }
    .m-status-pill.completed { background: #d1fae5; color: #065f46; }
    .m-title { font-size: 15px; font-weight: 800; color: var(--t1); margin: 2px 0; }
    .m-links { display: flex; gap: 12px; margin-top: 5px; }
    .m-link { font-size: 11px; font-weight: 700; color: var(--t4); text-decoration: none; display: flex; align-items: center; gap: 4px; }
    .m-link.active { color: var(--accent); }
    .m-footer { display: flex; align-items: center; justify-content: flex-end; }
    .m-actions { display: flex; gap: 6px; }
    .act-btn { width: 30px; height: 30px; border-radius: 8px; background: var(--bg4); color: var(--t3); display: flex; align-items: center; justify-content: center; transition: 0.2s; font-size: 13px; text-decoration: none; }
    .act-btn:hover { background: var(--bg3); color: var(--accent); }
    .act-btn.danger:hover { color: #dc2626; }
</style>

@endsection
