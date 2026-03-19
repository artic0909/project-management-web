{{--
    admin/components/multiselect-assets.blade.php
    Reusable multi-select styles + JS. Include at the bottom of any page that uses ms-wrap.
--}}

<style>
    .ms-wrap {
        position: relative;
    }

    /* Prevent parent cards from clipping the dropdown */
    .dash-card:has(.ms-wrap.open),
    .modal-box:has(.ms-wrap.open) {
        overflow: visible !important;
    }

    .ms-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        background: var(--bg3);
        border: 1px solid var(--b1);
        border-radius: var(--r-sm);
        padding: 7px 10px;
        cursor: pointer;
        min-height: 42px;
        transition: var(--transition);
    }

    .ms-trigger:hover,
    .ms-wrap.open .ms-trigger {
        border-color: var(--accent);
        background: var(--bg2);
    }

    .ms-wrap.open .ms-trigger {
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .1);
    }

    .ms-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        flex: 1;
        min-width: 0;
    }

    .ms-placeholder {
        font-size: 13px;
        color: var(--t4);
    }

    .ms-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: var(--accent-bg);
        border: 1px solid rgba(99, 102, 241, .25);
        color: var(--accent);
        font-size: 12px;
        font-weight: 600;
        padding: 2px 8px 2px 4px;
        border-radius: 20px;
        white-space: nowrap;
    }

    .ms-pill-ava {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .ms-pill-x {
        font-size: 10px;
        color: var(--accent);
        cursor: pointer;
        opacity: .7;
        line-height: 1;
        padding: 0 0 0 2px;
    }

    .ms-pill-x:hover {
        opacity: 1;
    }

    .ms-arrow {
        font-size: 11px;
        color: var(--t3);
        transition: transform .2s ease;
        flex-shrink: 0;
    }

    .ms-wrap.open .ms-arrow {
        transform: rotate(180deg);
    }

    .ms-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        background: var(--bg2);
        border: 1px solid var(--b2);
        border-radius: var(--r);
        box-shadow: 0 8px 32px rgba(0, 0, 0, .2);
        z-index: 9999;
        overflow: visible;
        /* don't clip the list */
        animation: msDropIn .15s ease;
    }

    @keyframes msDropIn {
        from {
            opacity: 0;
            transform: translateY(-6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .ms-wrap.open .ms-dropdown {
        display: block;
    }

    .ms-search-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-bottom: 1px solid var(--b1);
    }

    .ms-search-wrap i {
        color: var(--t3);
        font-size: 12px;
        flex-shrink: 0;
    }

    .ms-search {
        background: none;
        border: none;
        outline: none;
        font-size: 13px;
        color: var(--t1);
        font-family: var(--font);
        width: 100%;
    }

    .ms-search::placeholder {
        color: var(--t4);
    }

    .ms-opts {
        max-height: 320px;
        overflow-y: auto;
        padding: 4px;
        scrollbar-width: thin;
        scrollbar-color: var(--b2) transparent;
    }

    .ms-opt {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 8px 10px;
        border-radius: var(--r-sm);
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        color: var(--t2);
        transition: var(--transition);
        user-select: none;
    }

    .ms-opt:hover {
        background: var(--bg4);
        color: var(--t1);
    }

    .ms-opt input[type="checkbox"] {
        width: 14px;
        height: 14px;
        accent-color: var(--accent);
        cursor: pointer;
        flex-shrink: 0;
    }

    .ms-opt.hidden {
        display: none;
    }

    .ms-ava {
        width: 26px;
        height: 26px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .ms-opt:has(input:checked) {
        background: var(--accent-bg);
        color: var(--accent);
    }
</style>

<script>
    function toggleMs(wrapId) {
        const wrap = document.getElementById(wrapId);
        const isOpen = wrap.classList.contains('open');
        document.querySelectorAll('.ms-wrap.open').forEach(w => {
            if (w.id !== wrapId) w.classList.remove('open');
        });
        wrap.classList.toggle('open', !isOpen);
    }

    function updateMs(wrapId) {
        const wrap = document.getElementById(wrapId);
        const pillsEl = wrap.querySelector('.ms-pills');
        const checkboxes = wrap.querySelectorAll('.ms-opt input[type="checkbox"]:checked');

        pillsEl.innerHTML = '';

        if (checkboxes.length === 0) {
            pillsEl.innerHTML = '<span class="ms-placeholder">Select members…</span>';
            return;
        }

        checkboxes.forEach(cb => {
            const label = cb.closest('.ms-opt');
            const ava = label.querySelector('.ms-ava');
            const name = cb.value;
            const bg = ava ? ava.style.background : '#6366f1';
            const initials = name.split(' ').map(w => w[0]).join('');

            const pill = document.createElement('span');
            pill.className = 'ms-pill';
            pill.innerHTML = `
                <span class="ms-pill-ava" style="background:${bg}">${initials}</span>
                ${name}
                <span class="ms-pill-x" onclick="removeMsPill(event,'${wrapId}','${name}')">✕</span>
            `;
            pillsEl.appendChild(pill);
        });
    }

    function removeMsPill(event, wrapId, value) {
        event.stopPropagation();
        const wrap = document.getElementById(wrapId);
        const cb = [...wrap.querySelectorAll('.ms-opt input[type="checkbox"]')]
            .find(c => c.value === value);
        if (cb) cb.checked = false;
        updateMs(wrapId);
    }

    function filterMs(input, dropdownId) {
        const q = input.value.toLowerCase();
        document.querySelectorAll(`#${dropdownId} .ms-opt`).forEach(opt => {
            opt.classList.toggle('hidden', !opt.textContent.toLowerCase().includes(q));
        });
    }

    document.addEventListener('click', function(e) {
        document.querySelectorAll('.ms-wrap.open').forEach(wrap => {
            if (!wrap.contains(e.target)) wrap.classList.remove('open');
        });
    });
</script>