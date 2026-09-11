@extends('admin.layout.app')

@php
    $pageTitle = isset($routePrefix) ? ($routePrefix == 'sale' ? 'Sales Notes' : ($routePrefix == 'developer' ? 'Developer Notes' : 'Management Notes')) : 'Management Notes';
@endphp

@section('title', $pageTitle)

@section('content')

<style>
    /* ─── NOTES PAGE RESPONSIVE STYLES ─── */
    .notes-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 22px;
    }

    .notes-layout-grid {
        display: grid;
        grid-template-columns: 360px minmax(0, 1fr);
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 1100px) {
        .notes-layout-grid {
            grid-template-columns: 320px minmax(0, 1fr);
            gap: 18px;
        }
    }

    @media (max-width: 992px) {
        .notes-layout-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
    }

    .notes-sidebar-col, .notes-content-col {
        min-width: 0;
        width: 100%;
    }

    @media (min-width: 993px) {
        .sticky-note-form {
            position: sticky;
            top: calc(var(--topbar-h, 58px) + 20px);
            z-index: 10;
        }
    }

    /* Form Styling */
    .form-row {
        margin-bottom: 16px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-lbl {
        font-size: 12px;
        font-weight: 700;
        color: var(--t2);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
    }

    .form-inp {
        width: 100%;
        border: 1px solid var(--b3);
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 13.5px;
        background: var(--bg3);
        color: var(--t1);
        transition: var(--transition);
        outline: none;
    }

    .form-inp:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-bg);
    }

    textarea.form-inp {
        resize: vertical;
        min-height: 110px;
    }

    /* Note Card & Timeline */
    .notes-timeline {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .note-card {
        border: 1px solid var(--b3);
        border-radius: 12px;
        padding: 18px;
        background: var(--bg2);
        transition: var(--transition);
    }

    .note-card:hover {
        border-color: var(--b2);
        box-shadow: var(--shadow-sm);
    }

    .note-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .note-author {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .note-ava {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: linear-gradient(135deg, #6366f1 0%, #06b6d4 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 800;
        font-size: 14px;
        flex-shrink: 0;
    }

    .author-name {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--t1);
        line-height: 1.3;
    }

    .author-date {
        font-size: 11.5px;
        color: var(--t3);
        margin-top: 2px;
    }

    .btn-delete-note {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid rgba(239, 68, 68, 0.2);
        background: rgba(239, 68, 68, 0.08);
        color: #ef4444;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        padding: 0;
    }

    .btn-delete-note:hover {
        background: #ef4444;
        color: #fff;
        border-color: #ef4444;
    }

    .note-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--t1);
        margin-bottom: 6px;
        letter-spacing: -0.2px;
    }

    .note-body-text {
        font-size: 13.5px;
        line-height: 1.6;
        color: var(--t2);
        white-space: pre-line;
        word-break: break-word;
    }

    /* Attachments Grid */
    .note-attachments {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid var(--b3);
    }

    .attach-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background: var(--bg3);
        border: 1px solid var(--b3);
        border-radius: 8px;
        text-decoration: none;
        transition: var(--transition);
        max-width: 100%;
    }

    .attach-chip:hover {
        border-color: var(--accent);
        background: var(--bg2);
    }

    .attach-name {
        font-size: 12px;
        font-weight: 600;
        color: var(--t2);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    @media (max-width: 480px) {
        .note-card {
            padding: 14px;
        }
        .attach-name {
            max-width: 160px;
        }
    }
</style>

<main class="page-area" id="pageArea">
    <div class="page">
        <!-- PAGE HEADER -->
        <div class="notes-page-header">
            <div>
                <h1 class="page-title">{{ $pageTitle }}</h1>
                <p class="page-desc">Internal documentation, progress updates, and shared notes</p>
            </div>
        </div>

        <!-- MAIN LAYOUT -->
        <div class="notes-layout-grid">
            
            <!-- LEFT COLUMN: Create Note Form (Sticky on Desktop) -->
            <div class="notes-sidebar-col">
                <div class="dash-card sticky-note-form">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-pencil-square"></i> Create New Note</div>
                    </div>
                    <div class="card-body">
                        <form id="noteForm" action="{{ route(($routePrefix ?? 'admin') . '.notes.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <label class="form-lbl">Title (Optional)</label>
                                <input type="text" name="title" class="form-inp" placeholder="e.g. Weekly Roadmap Update">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Note Content <span style="color:#ef4444">*</span></label>
                                <textarea name="content" class="form-inp" rows="5" placeholder="Write your note, update, or instructions here..." required></textarea>
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Attachments</label>
                                <input type="file" name="attachments[]" class="form-inp" style="padding: 6px 10px; font-size: 12.5px;" multiple>
                                <div style="font-size:11px; color:var(--t3); margin-top:4px;">Max 10MB per file. (Images, PDF, Docs, Zip supported)</div>
                            </div>
                            <button type="submit" class="btn-primary-solid" style="width: 100%; height: 42px; font-size: 14px; font-weight: 600; justify-content: center; margin-top: 8px;">
                                <i class="bi bi-send-fill"></i> Post Note
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Notes History Timeline -->
            <div class="notes-content-col">
                <div class="dash-card">
                    <div class="card-head" style="flex-wrap: wrap; gap: 10px;">
                        <div class="card-title"><i class="bi bi-clock-history"></i> Note History</div>
                        <div class="card-sub" style="font-size: 12px; color: var(--t3);">Showing {{ $notes->count() }} of {{ $notes->total() }} recorded notes</div>
                    </div>
                    <div class="card-body">
                        <div class="notes-timeline">
                            @forelse($notes as $note)
                                <div class="note-card">
                                    <div class="note-header">
                                        <div class="note-author">
                                            <div class="note-ava">
                                                {{ strtoupper(substr($note->createdBy->name ?? 'A', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="author-name">{{ $note->createdBy->name ?? 'Administrator' }}</div>
                                                <div class="author-date">
                                                    {{ $note->created_at->format('d M Y, h:i A') }} • <span style="color:var(--t3);">{{ $note->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <button type="button" class="btn-delete-note" title="Delete Note" onclick="confirmSingleDelete('{{ route(($routePrefix ?? 'admin') . '.notes.destroy', $note->id) }}')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </div>

                                    @if($note->title)
                                        <h3 class="note-title">{{ $note->title }}</h3>
                                    @endif

                                    <div class="note-body-text">{{ $note->content }}</div>

                                    @if(!empty($note->attachments))
                                        <div class="note-attachments">
                                            @foreach($note->attachments as $file)
                                                @php 
                                                    $isImage = strpos($file['type'] ?? '', 'image') !== false;
                                                    $isPdf = strpos($file['type'] ?? '', 'pdf') !== false;
                                                    $isDoc = strpos($file['type'] ?? '', 'word') !== false || strpos($file['type'] ?? '', 'officedocument') !== false;
                                                    $isZip = strpos($file['type'] ?? '', 'zip') !== false || strpos($file['type'] ?? '', 'compressed') !== false;
                                                @endphp
                                                <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" rel="noopener noreferrer" class="attach-chip">
                                                    <i class="bi {{ $isImage ? 'bi-image' : ($isPdf ? 'bi-file-earmark-pdf-fill' : ($isZip ? 'bi-file-zip-fill' : ($isDoc ? 'bi-file-earmark-word-fill' : 'bi-file-earmark-text'))) }}" style="font-size: 14px; color: {{ $isPdf ? '#ef4444' : ($isImage ? '#10b981' : ($isZip ? '#f59e0b' : 'var(--accent)')) }}"></i>
                                                    <span class="attach-name">{{ $file['name'] }}</span>
                                                    <i class="bi bi-download" style="font-size: 11px; color: var(--t3); margin-left: auto;"></i>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div style="text-align: center; padding: 48px 20px; background: var(--bg3); border-radius: 12px; border: 1px dashed var(--b3);">
                                    <div style="width: 52px; height: 52px; background: var(--bg4); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; color: var(--t3); font-size: 24px;">
                                        <i class="bi bi-sticky"></i>
                                    </div>
                                    <h3 style="font-size: 15px; font-weight: 700; color: var(--t1); margin-bottom: 6px;">No Notes Found</h3>
                                    <p style="font-size: 13px; color: var(--t3); margin: 0;">Start by posting your first administrative note using the form.</p>
                                </div>
                            @endforelse

                            @if($notes->hasPages())
                                <div style="margin-top: 10px;">
                                    {{ $notes->links('admin.includes.pagination') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- SINGLE DELETE MODAL -->
<div class="modal-backdrop" id="deleteModal">
    <div class="modal-box" style="width: min(420px, calc(100vw - 32px));" onclick="event.stopPropagation()">
        <div class="modal-hd" style="border-bottom: 1px solid rgba(239, 68, 68, 0.2);">
            <span style="color:#ef4444; font-weight: 700;"><i class="bi bi-exclamation-triangle-fill"></i> Delete Note</span>
            <button type="button" class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-bd" style="text-align: center; padding: 28px 20px;">
            <div style="width: 60px; height: 60px; background: rgba(239, 68, 68, 0.12); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="bi bi-trash3-fill" style="font-size: 26px; color: #ef4444;"></i>
            </div>
            <h3 style="margin: 0 0 8px; font-size: 18px; font-weight: 700; color: var(--t1);">Are you sure?</h3>
            <p style="margin: 0; font-size: 13.5px; color: var(--t3); line-height: 1.6;">Are you sure you want to delete this note?<br>This action <strong style="color:#ef4444;">cannot be undone.</strong></p>
        </div>
        <div class="modal-ft" style="border-top: 1px solid var(--b3); justify-content: flex-end; gap: 10px;">
            <button type="button" class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
            <form id="deleteRecordForm" method="POST" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" style="background:#ef4444; color:#fff; border:none; border-radius:8px; padding:8px 18px; font-size:13.5px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:6px;">
                    <i class="bi bi-trash3-fill"></i> Confirm Deletion
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmSingleDelete(url) {
        document.getElementById('deleteRecordForm').action = url;
        const m = document.getElementById('deleteModal');
        if (m) {
            m.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(id) {
        const m = document.getElementById(id);
        if (m) {
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

    // Client-side file size validation to prevent 413 Request Entity Too Large
    const noteForm = document.getElementById('noteForm');
    if (noteForm) {
        noteForm.addEventListener('submit', function(e) {
            const fileInput = document.querySelector('input[name="attachments[]"]');
            if (fileInput && fileInput.files.length > 0) {
                let totalSize = 0;
                const maxPerFile = 10 * 1024 * 1024; // 10MB
                const maxTotal = 10 * 1024 * 1024; // 10MB total
                
                for (let i = 0; i < fileInput.files.length; i++) {
                    if (fileInput.files[i].size > maxPerFile) {
                        alert('File "' + fileInput.files[i].name + '" is too large. Maximum size is 10MB per file.');
                        e.preventDefault();
                        return;
                    }
                    totalSize += fileInput.files[i].size;
                }
                
                if (totalSize > maxTotal) {
                    alert('Total upload size exceeds the limit (10MB). Please upload smaller or fewer files.');
                    e.preventDefault();
                    return;
                }
            }
        });
    }
</script>
@endsection
