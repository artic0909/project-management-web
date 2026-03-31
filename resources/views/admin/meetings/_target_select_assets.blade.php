@php
    $leadsData = $leads->map(fn($l) => [
        'id' => $l->id,
        'name' => $l->company ?? 'Unknown Company',
        'sub' => ($l->contact_person ? $l->contact_person : '') . ($l->emails && count($l->emails) > 0 ? ' • ' . $l->emails[0] : ''),
        'type' => 'lead'
    ]);
    
    $ordersData = $orders->map(fn($o) => [
        'id' => $o->id,
        'name' => 'Order #' . $o->id,
        'sub' => ($o->lead->company ?? 'No Company') . ($o->lead->contact_person ? ' • ' . $o->lead->contact_person : ''),
        'type' => 'order'
    ]);
    
    $projectsData = $projects->map(fn($p) => [
        'id' => $p->id,
        'name' => $p->project_name ?? 'Unnamed Project',
        'sub' => $p->project_id . ($p->company_name ? ' • ' . $p->company_name : ''),
        'type' => 'project'
    ]);
@endphp

<style>
    .target-select-wrap { position: relative; width: 100%; border-radius: var(--r); }
    .ts-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--bg3);
        border: 1px solid var(--b1);
        border-radius: var(--r);
        padding: 10px 14px;
        cursor: pointer;
        transition: var(--transition);
        min-height: 42px;
    }
    .ts-trigger:hover { border-color: var(--accent); }
    .ts-selected-text { font-size: 13.5px; color: var(--t1); font-weight: 600; flex: 1; }
    .ts-placeholder { color: var(--t4); font-weight: 500; }
    .ts-arrow { color: var(--t4); font-size: 12px; transition: 0.2s; }
    .target-select-wrap.open .ts-arrow { transform: rotate(180deg); }

    .ts-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        left: 0; right: 0;
        background: var(--bg2);
        border: 1px solid var(--b2);
        border-radius: var(--r-lg);
        box-shadow: var(--shadow-lg);
        z-index: 10000;
        overflow: hidden;
        animation: tsIn 0.2s ease;
    }
    @keyframes tsIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    .target-select-wrap.open .ts-dropdown { display: block; }

    .ts-search-box { padding: 12px; border-bottom: 1px solid var(--b1); display: flex; align-items: center; gap: 10px; background: var(--bg2); }
    .ts-search-box i { color: var(--t4); font-size: 13px; }
    .ts-search-inp { border: none; background: none; outline: none; flex: 1; color: var(--t1); font-size: 13.5px; }

    .ts-options { max-height: 320px; overflow-y: auto; padding: 6px; }
    .ts-opt {
        padding: 10px 12px;
        border-radius: var(--r-sm);
        cursor: pointer;
        transition: 0.15s;
        display: flex;
        align-items: center;
        gap: 12px;
        border: 1px solid transparent;
    }
    .ts-opt:hover { background: var(--bg3); border-color: var(--b1); }
    .ts-opt.active { background: var(--accent-bg); border-color: var(--accent); }
    
    .ts-ava { width: 32px; height: 32px; border-radius: 9px; background: var(--bg4); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; color: var(--t2); flex-shrink: 0; }
    .ts-info { flex: 1; min-width: 0; }
    .ts-name { font-size: 13px; font-weight: 700; color: var(--t1); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ts-sub { font-size: 10px; color: var(--t4); font-weight: 600; display: flex; align-items: center; gap: 8px; }
    .ts-tag { font-size: 9px; font-weight: 800; text-transform: uppercase; color: var(--accent); opacity: 0.8; }
    .ts-opt.hidden { display: none; }
</style>

<script>
    // Universal data passed from PHP
    const TS_DATA = {
        leads: @json($leadsData),
        orders: @json($ordersData),
        projects: @json($projectsData)
    };

    let activeType = 'lead';

    function initTs() {
        const typeSelect = document.getElementById('meetingType');
        if (typeSelect) {
            activeType = typeSelect.value + 's'; // e.g. lead -> leads
            renderTsOptions();
        }
    }

    function renderTsOptions() {
        const optsContainer = document.querySelector('.ts-options');
        if (!optsContainer) return;

        const data = TS_DATA[activeType] || [];
        optsContainer.innerHTML = '';

        data.forEach(item => {
            const initials = (item.name || "P").substring(0, 1).toUpperCase();
            const opt = document.createElement('div');
            opt.className = 'ts-opt';
            opt.dataset.id = item.id;
            opt.onclick = () => selectTsTarget(item);
            
            opt.innerHTML = `
                <div class="ts-ava">${initials}</div>
                <div class="ts-info">
                    <div class="ts-name">${item.name}</div>
                    <div class="ts-sub"><span class="ts-tag">${item.type}</span> <span>${item.sub}</span></div>
                </div>
            `;
            optsContainer.appendChild(opt);
        });
    }

    function selectTsTarget(item) {
        const wrap = document.querySelector('.target-select-wrap');
        const trigger = wrap.querySelector('.ts-selected-text');
        
        trigger.innerHTML = `${item.name} <span style="color:var(--t4);font-weight:500;margin-left:8px;font-size:11px;">(${item.sub})</span>`;
        
        // Update hidden inputs
        document.getElementById('hidden_lead_id').value = item.type === 'lead' ? item.id : '';
        document.getElementById('hidden_order_id').value = item.type === 'order' ? item.id : '';
        document.getElementById('hidden_project_id').value = item.type === 'project' ? item.id : '';
        
        wrap.classList.remove('open');
        
        // Highlight active
        wrap.querySelectorAll('.ts-opt').forEach(opt => {
            opt.classList.toggle('active', opt.dataset.id == item.id);
        });
    }

    function toggleTs() {
        document.querySelector('.target-select-wrap').classList.toggle('open');
        if (document.querySelector('.target-select-wrap').classList.contains('open')) {
            document.querySelector('.ts-search-inp').focus();
        }
    }

    function filterTs(q) {
        q = q.toLowerCase();
        document.querySelectorAll('.ts-opt').forEach(opt => {
            const txt = opt.textContent.toLowerCase();
            opt.classList.toggle('hidden', !txt.includes(q));
        });
    }

    function updateDevSectionVisibility() {
        const typeSelect = document.getElementById('meetingType');
        const devSection = document.getElementById('devSection');
        if (typeSelect && devSection) {
            devSection.style.display = typeSelect.value === 'project' ? 'block' : 'none';
        }
    }

    function toggleTargets(isInitial = false) {
        const typeSelect = document.getElementById('meetingType');
        if (!typeSelect) return;
        
        activeType = typeSelect.value + 's';
        renderTsOptions();
        updateDevSectionVisibility();
        
        if (!isInitial) {
            // Clear selection text if this was a manual user change
            const selectedText = document.querySelector('.ts-selected-text');
            if (selectedText) {
                selectedText.innerHTML = '<span class="ts-placeholder">— Select Target —</span>';
            }
            // Clear hidden inputs
            if (document.getElementById('hidden_lead_id')) document.getElementById('hidden_lead_id').value = '';
            if (document.getElementById('hidden_order_id')) document.getElementById('hidden_order_id').value = '';
            if (document.getElementById('hidden_project_id')) document.getElementById('hidden_project_id').value = '';
        }
    }

    // Handle type switching
    window.addEventListener('load', () => {
        initTs();
        // Initial setup for current type (crucial for Edit view)
        toggleTargets(true);
    });

    document.addEventListener('click', (e) => {
        const wrap = document.querySelector('.target-select-wrap');
        if (wrap && !wrap.contains(e.target)) wrap.classList.remove('open');
    });
</script>
