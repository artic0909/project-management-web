@php $routePrefix = $routePrefix ?? 'admin'; @endphp
{{-- Order Notes History Card --}}
<div class="dash-card">
    <div class="card-head" style="padding:16px 18px; border-bottom:1px solid var(--b1);">
        <div class="card-title">Order Notes History</div>
    </div>
    <div class="card-body" style="padding:18px;">
        <form action="{{ route($routePrefix . '.order-notes.store', $order->id) }}" method="POST" style="margin-bottom:20px;">
            @csrf
            <div style="position:relative;">
                <textarea name="notes" class="form-inp" rows="3" placeholder="Add internal order note..." style="padding-right:45px; border-radius:12px; font-size:13px; min-height:80px;"></textarea>
                <button type="submit" style="position:absolute; bottom:10px; right:12px; width:32px; height:32px; border-radius:50%; background:var(--accent); border:none; color:#fff; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s; box-shadow:0 4px 12px rgba(99,102,241,0.3);">
                    <i class="bi bi-send-fill" style="font-size:14px;"></i>
                </button>
            </div>
        </form>

        <div class="notes-timeline">
            @forelse($order->notes_history as $note)
                <div class="note-item">
                    <div class="note-header">
                        <div class="note-author">
                            <div class="mini-ava">{{ strtoupper(substr($note->createdBy->name ?? 'S', 0, 1)) }}</div>
                            <div class="author-info">
                                <span class="name">{{ $note->createdBy->name ?? 'System' }}</span>
                                <span class="role">{{ $note->created_by_type == \App\Models\Admin::class ? 'Admin' : 'Sale' }}</span>
                            </div>
                        </div>
                        <div class="note-actions">
                            <button type="button" class="not-btn" onclick="openEditOrderNoteModal({{ $note->id }}, '{{ addslashes($note->notes) }}')"><i class="bi bi-pencil"></i></button>
                            <button type="button" class="not-btn danger" onclick="confirmDeleteOrderNote('{{ route($routePrefix . '.order-notes.destroy', $note->id) }}')"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                    <div class="note-content">{{ $note->notes }}</div>
                    <div class="note-footer">
                        {{ $note->created_at->diffForHumans() }}
                        @if($note->updated_at > $note->created_at)
                            <span class="ed">• Ed</span>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align:center; padding:10px; color:var(--t4); font-size:12px;">No order notes recorded.</div>
            @endforelse
        </div>
    </div>
</div>
