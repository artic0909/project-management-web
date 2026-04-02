<style>
    /* Order Notes Timeline Styling */
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
    function openEditOrderNoteModal(id, notes) {
        const modalEl = document.getElementById('editOrderNoteModal');
        const form = document.getElementById('editOrderNoteForm');
        const textarea = form.querySelector('textarea');
        const prefix = '{{ $routePrefix ?? "admin" }}';
        
        textarea.value = notes;
        form.action = `/${prefix}/order-notes/${id}`;
        
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }

    function confirmDeleteOrderNote(url) {
        const modalEl = document.getElementById('deleteOrderNoteModal');
        const form = document.getElementById('deleteOrderNoteForm');
        form.action = url;
        
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }
</script>

{{-- Edit Order Note Modal --}}
<div class="modal fade" id="editOrderNoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg2); border: 1px solid var(--b2); border-radius: 12px; box-shadow: var(--shadow-lg);">
            <div class="modal-header" style="border-bottom: 1px solid var(--b1); padding: 16px 20px;">
                <h5 class="modal-title" style="font-size: 16px; font-weight: 700; color: var(--t1);">Edit Order Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: var(--close-filter);"></button>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <form id="editOrderNoteForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label" style="font-size: 12px; font-weight: 700; color: var(--t3); text-transform: uppercase; margin-bottom: 8px; display: block;">Update your note</label>
                        <textarea name="notes" class="form-control" rows="5" required style="background: var(--bg3); border: 1px solid var(--b1); color: var(--t1); border-radius: 8px; font-size: 14px; padding: 12px; transition: var(--transition);"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--b1); padding: 16px 20px; gap: 10px;">
                <button type="button" class="btn btn-link" data-bs-dismiss="modal" style="color: var(--t3); text-decoration: none; font-weight: 600; font-size: 14px;">Cancel</button>
                <button type="submit" form="editOrderNoteForm" class="btn-primary-solid" style="padding: 8px 18px; border-radius: 8px; font-weight: 700; font-size: 14px;">Update Note</button>
            </div>
        </div>
    </div>
</div>

{{-- Delete Order Note Confirmation Modal --}}
<div class="modal fade" id="deleteOrderNoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="background: var(--bg2); border: 1px solid #fee2e2; border-radius: 16px; box-shadow: var(--shadow-lg);">
            <div class="modal-body" style="padding: 32px 24px; text-align: center;">
                <div style="width: 64px; height: 64px; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <i class="bi bi-trash3-fill" style="font-size: 28px; color: #ef4444;"></i>
                </div>
                <h3 style="margin: 0 0 10px; font-size: 18px; font-weight: 800; color: var(--t1);">Delete Order Note?</h3>
                <p style="margin: 0; font-size: 14px; color: var(--t3); line-height: 1.6;">This action is permanent and cannot be undone.</p>
            </div>
            <div class="modal-footer" style="border: none; padding: 0 24px 24px; display: flex; gap: 12px; justify-content: center;">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="flex: 1; background: var(--bg3); border: 1px solid var(--b1); color: var(--t2); font-weight: 600; border-radius: 10px; padding: 10px;">Cancel</button>
                <form id="deleteOrderNoteForm" method="POST" style="flex: 1; margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width: 100%; height: 100%; background: #ef4444; border: none; color: #fff; font-weight: 700; border-radius: 10px; padding: 10px;">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
