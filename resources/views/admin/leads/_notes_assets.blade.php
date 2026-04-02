<style>
    /* Notes Timeline Styling */
    .notes-timeline { display: flex; flex-direction: column; gap: 12px; }
    .note-item { padding: 12px; background: var(--bg3); border: 1px solid var(--b1); border-radius: 12px; }
    .note-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px; }
    .note-author { display: flex; align-items: center; gap: 8px; }
    .mini-ava { width: 24px; height: 24px; border-radius: 6px; background: var(--bg4); display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 800; color: var(--t2); }
    .author-info { display: flex; flex-direction: column; }
    .author-info .name { font-size: 12px; font-weight: 700; color: var(--t1); line-height: 1; }
    .author-info .role { font-size: 9px; font-weight: 600; color: var(--t4); text-transform: uppercase; margin-top: 1px; }
    .note-actions { display: flex; gap: 2px; }
    .not-btn { width: 22px; height: 22px; border-radius: 4px; border: none; background: transparent; color: var(--t4); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; font-size: 11px; }
    .not-btn:hover { background: var(--bg4); color: var(--accent); }
    .not-btn.danger:hover { color: #ef4444; }
    .note-content { font-size: 12.5px; line-height: 1.5; color: var(--t2); white-space: pre-wrap; margin-bottom: 6px; }
    .note-footer { font-size: 10px; color: var(--t4); font-weight: 600; display: flex; align-items: center; gap: 4px; }
    .note-footer .ed { color: var(--accent); }
</style>

<script>
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }

    function openEditNoteModal(id, notes) {
        const modal = document.getElementById('editNoteModal');
        const form = document.getElementById('editNoteForm');
        const textarea = form.querySelector('textarea');
        
        textarea.value = notes;
        form.action = `/admin/lead-notes/${id}`;
        modal.style.display = 'flex';
    }
</script>

{{-- Edit Note Modal --}}
<div class="modal-backdrop" id="editNoteModal" onclick="closeModal('editNoteModal')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-hd">
            <span>Edit Note</span>
            <button class="modal-close" onclick="closeModal('editNoteModal')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-bd">
            <form id="editNoteForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <label class="form-lbl">Update your note</label>
                    <textarea name="notes" class="form-inp" rows="5" required></textarea>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                    <button type="button" class="btn-ghost" onclick="closeModal('editNoteModal')">Cancel</button>
                    <button type="submit" class="btn-primary-solid">Update Note</button>
                </div>
            </form>
        </div>
    </div>
</div>
