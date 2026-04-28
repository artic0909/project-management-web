@extends('admin.layout.app')

@section('title', 'Admin Notes')

@section('content')
<main class="page-area">
    <div class="page-header">
        <div>
            <h1 class="page-title">Management Notes</h1>
            <p class="page-desc">Internal documentation and shared notes for administrative staff.</p>
        </div>
    </div>

    <div class="dash-grid" style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 24px;">
        <div style="grid-column: span 4;">
            <div class="dash-card" style="position: sticky; top: 20px;">
                <div class="card-head">
                    <div class="card-title">Create New Note</div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.notes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <label class="form-lbl">Title (Optional)</label>
                            <input type="text" name="title" class="form-inp" placeholder="e.g., Weekly Plan">
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Note Content <span style="color:#ef4444">*</span></label>
                            <textarea name="content" class="form-inp" rows="6" placeholder="Write your note here..." required></textarea>
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Attachments</label>
                            <input type="file" name="attachments[]" class="form-inp" multiple>
                            <div style="font-size:10px; color:var(--t4); margin-top:4px;">Max 10MB per file. Images, PDF, Docs, Zip supported.</div>
                        </div>
                        <button type="submit" class="btn-primary-solid w-100" style="justify-content:center; margin-top:10px; width: 100%;">
                            <i class="bi bi-plus-lg"></i> Post Note
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div style="grid-column: span 8;">
            <div class="dash-card">
                <div class="card-head">
                    <div class="card-title">Note History</div>
                    <div class="card-sub" style="margin-left: auto; font-size: 12px; color: var(--t4);">Showing {{ $notes->count() }} of {{ $notes->total() }} recorded notes</div>
                </div>
                <div class="card-body">
                    <div class="notes-timeline">
                        @forelse($notes as $note)
                            <div class="note-item" style="margin-bottom: 24px; border: 1px solid var(--b1); border-radius: 16px; padding: 20px; background: var(--bg2); position: relative;">
                                <div class="note-header" style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                                    <div class="note-author" style="display: flex; align-items: center; gap: 12px;">
                                        <div class="mini-ava" style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, #6366f1, #06b6d4); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800; font-size: 16px;">
                                            {{ strtoupper(substr($note->createdBy->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <div class="author-info">
                                            <div style="font-size: 14px; font-weight: 700; color: var(--t1);">{{ $note->createdBy->name ?? 'Administrator' }}</div>
                                            <div style="font-size: 11px; color: var(--t4); font-weight: 600;">{{ $note->created_at->format('d M Y, h:i A') }} ({{ $note->created_at->diffForHumans() }})</div>
                                        </div>
                                    </div>
                                    <div class="note-actions">
                                        <button type="button" onclick="confirmSingleDelete('{{ route('admin.notes.destroy', $note->id) }}')" style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid #fee2e2; background: #fff5f5; color: #ef4444; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </div>

                                @if($note->title)
                                    <h3 style="font-size: 16px; font-weight: 800; color: var(--t1); margin-bottom: 8px; letter-spacing: -0.2px;">{{ $note->title }}</h3>
                                @endif

                                <div class="note-content" style="font-size: 14px; line-height: 1.6; color: var(--t2); white-space: pre-line; margin-bottom: 15px;">{{ $note->content }}</div>

                                @if(!empty($note->attachments))
                                    <div class="note-attachments" style="display: flex; flex-wrap: wrap; gap: 10px; padding-top: 15px; border-top: 1px solid var(--b2);">
                                        @foreach($note->attachments as $file)
                                            @php 
                                                $isImage = strpos($file['type'], 'image') !== false;
                                                $isPdf = strpos($file['type'], 'pdf') !== false;
                                                $isDoc = strpos($file['type'], 'word') !== false || strpos($file['type'], 'officedocument') !== false;
                                                $isZip = strpos($file['type'], 'zip') !== false || strpos($file['type'], 'compressed') !== false;
                                            @endphp
                                            <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" style="display: flex; align-items: center; gap: 8px; padding: 6px 12px; background: var(--bg3); border: 1px solid var(--b1); border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--b1)'">
                                                <i class="bi {{ $isImage ? 'bi-image' : ($isPdf ? 'bi-file-earmark-pdf-fill' : ($isZip ? 'bi-file-zip-fill' : ($isDoc ? 'bi-file-earmark-word-fill' : 'bi-file-earmark-text'))) }}" style="color: {{ $isPdf ? '#ef4444' : ($isImage ? '#10b981' : ($isZip ? '#f59e0b' : 'var(--accent)')) }}"></i>
                                                <span style="font-size: 12px; font-weight: 600; color: var(--t2);">{{ \Illuminate\Support\Str::limit($file['name'], 25) }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div style="text-align: center; padding: 60px 40px; background: var(--bg3); border-radius: 20px; border: 2px dashed var(--b1);">
                                <div style="width: 60px; height: 60px; background: var(--bg4); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                                    <i class="bi bi-sticky" style="font-size: 30px; color: var(--t4);"></i>
                                </div>
                                <h3 style="font-size: 16px; font-weight: 700; color: var(--t1); margin-bottom: 8px;">No notes found</h3>
                                <p style="font-size: 14px; color: var(--t3); margin: 0;">Start by creating your first administrative note using the form on the left.</p>
                            </div>
                        @endforelse

                        <div class="mt-4">
                            {{ $notes->links('admin.includes.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .form-row { margin-bottom: 20px; }
    .form-lbl { display: block; font-size: 12px; font-weight: 700; color: var(--t3); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
    .form-inp { width: 100%; border: 1px solid var(--b1); border-radius: 12px; padding: 12px 16px; font-size: 14px; background: var(--bg2); color: var(--t1); transition: all 0.2s; }
    .form-inp:focus { border-color: var(--accent); box-shadow: 0 0 0 4px rgba(99,102,241,0.1); outline: none; }
    .btn-primary-solid { display: flex; align-items: center; gap: 8px; background: var(--accent); color: #fff; border: none; border-radius: 12px; padding: 12px 20px; font-size: 15px; font-weight: 700; cursor: pointer; transition: all 0.2s; }
    .btn-primary-solid:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(99,102,241,0.3); }
    
    .notes-timeline { display: flex; flex-direction: column; }
    
    /* Responsive grid */
    @media (max-width: 992px) {
        .dash-grid { grid-template-columns: 1fr !important; }
        .dash-grid > div { grid-column: span 12 !important; }
        .dash-card { position: static !important; }
    }
</style>

<!-- SINGLE DELETE MODAL -->
<div class="modal-backdrop" id="deleteModal">
    <div class="modal-box sm-box" onclick="event.stopPropagation()">
        <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
            <span style="color:#dc2626;">Delete Admin Note</span>
            <button class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-bd" style="text-align:center;padding:32px 24px;">
            <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
            </div>
            <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:var(--t1);">Delete Note?</h3>
            <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">Are you sure you want to delete this administrative note?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
        </div>
        <div class="modal-ft" style="border-top:1px solid #fecaca;">
            <button class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
            <form id="deleteRecordForm" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
            <button style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;" onclick="document.getElementById('deleteRecordForm').submit()">
                <i class="bi bi-trash3-fill"></i> Confirm Deletion
            </button>
        </div>
    </div>
</div>

<script>
    function confirmSingleDelete(url) {
        document.getElementById('deleteRecordForm').action = url;
        const m = document.getElementById('deleteModal');
        m.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        const m = document.getElementById(id);
        if(m) {
            m.classList.remove('open');
            document.body.style.overflow = 'auto';
        }
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal-backdrop')) {
            closeModal(event.target.id);
        }
    }
</script>
@endsection
