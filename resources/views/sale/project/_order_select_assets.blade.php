@php
    $orderData = $orders->map(function($o) {
        return [
            'id' => $o->id,
            'company_name' => $o->company_name,
            'client_name' => $o->client_name,
            'emails' => $o->emails,
            'phones' => $o->phones,
            'domain_name' => $o->domain_name,
            'plan_name' => $o->plan_name,
            'order_value' => $o->order_value,
            'mkt_username' => $o->mkt_username,
            'mkt_password' => $o->mkt_password,
            'mkt_starting_date' => $o->mkt_starting_date ? $o->mkt_starting_date->format('Y-m-d') : null,
            'delivery_date' => $o->delivery_date ? $o->delivery_date->format('Y-m-d') : null,
            'full_address' => $o->full_address,
            'created_at_fmt' => $o->created_at->format('d M Y')
        ];
    });
@endphp

<style>
    .order-select-wrap { position: relative; width: 100%; }
    .os-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--bg2);
        border: 1px solid var(--b1);
        border-radius: var(--r-sm);
        padding: 9px 12px;
        cursor: pointer;
        transition: var(--transition);
        min-height: 44px;
    }
    .os-trigger:hover { border-color: var(--accent); }
    .os-selected-text { font-size: 14px; color: var(--t1); font-weight: 500; flex: 1; }
    .os-placeholder { color: var(--t4); font-weight: 400; }
    
    .os-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 5px);
        left: 0; right: 0;
        background: #fff; /* Solid fallback */
        background-color: var(--bg1, #ffffff);
        opacity: 1 !important;
        border: 1px solid var(--b2);
        border-radius: var(--r);
        box-shadow: 0 10px 50px rgba(0,0,0,0.3);
        z-index: 10000;
        overflow: hidden;
        animation: osIn 0.2s ease;
    }
    @keyframes osIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    .order-select-wrap.open .os-dropdown { display: block; }

    .os-search-box { padding: 10px; border-bottom: 1px solid var(--b1); display: flex; align-items: center; gap: 10px; }
    .os-search-box i { color: var(--t4); }
    .os-search-inp { border: none; background: none; outline: none; flex: 1; color: var(--t1); font-size: 13px; }

    .os-options { max-height: 350px; overflow-y: auto; padding: 5px; }
    .os-opt {
        padding: 10px 12px;
        border-radius: var(--r-sm);
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .os-opt:hover { background: var(--bg3); }
    .os-opt.active { background: var(--accent-bg); border-left: 3px solid var(--accent); }
    
    .os-opt-main { font-weight: 600; color: var(--t1); font-size: 13.5px; display: flex; justify-content: space-between; align-items: center; }
    .os-opt-sub { font-size: 11px; color: var(--t3); display: flex; gap: 12px; }
    .os-date { font-size: 10px; color: var(--t4); background: var(--bg4); padding: 2px 6px; border-radius: 4px; font-weight: 700; }
    .os-opt.hidden { display: none; }
</style>

<script>
    const ORDERS = @json($orderData);

    function toggleOs() {
        document.querySelector('.order-select-wrap').classList.toggle('open');
    }

    function selectOrder(orderId) {
        const wrap = document.querySelector('.order-select-wrap');
        const trigger = wrap.querySelector('.os-selected-text');
        const hiddenInp = document.getElementById('selectedOrderId');
        
        if (orderId === '') {
            trigger.innerHTML = '<span class="os-placeholder">— Select Order (Optional) —</span>';
            hiddenInp.value = '';
        } else {
            const order = ORDERS.find(o => o.id == orderId);
            if (order) {
                trigger.innerHTML = `${order.company_name} <span style="color:var(--t4);font-weight:400;margin-left:8px;">(${order.domain_name})</span>`;
                hiddenInp.value = order.id;
                autoFillFromOrder(order);
            }
        }
        wrap.classList.remove('open');
        
        // Highlight active
        wrap.querySelectorAll('.os-opt').forEach(opt => {
            opt.classList.toggle('active', opt.dataset.id == orderId);
        });
    }

    function autoFillFromOrder(order) {
        // Basic Info
        setVal('input[name="project_name"]', order.domain_name);
        setVal('input[name="client_name"]', order.client_name);
        setVal('input[name="company_name"]', order.company_name);
        setVal('input[name="domain_name"]', order.domain_name);
        setVal('input[name="plan_name"]', order.plan_name);
        setVal('input[name="username"]', order.mkt_username);
        setVal('input[name="password"]', order.mkt_password);
        setVal('input[name="starting_date"]', order.mkt_starting_date);
        setVal('textarea[name="full_address"]', order.full_address);
        
        // Dates
        setVal('input[name="project_start_date"]', order.mkt_starting_date);
        setVal('input[name="expected_delivery_date"]', order.delivery_date);
        
        // Financials
        setVal('input[name="project_price"]', order.order_value);
        if (typeof calcRemaining === 'function') calcRemaining();

        // Emails & Phones (Special handle for multi-add)
        fillMultiContact('email-list', order.emails, 'email');
        fillMultiContact('phone-list', order.phones, 'phone');
    }

    function setVal(selector, val) {
        const el = document.querySelector(selector);
        if (el && val) el.value = val;
    }

    function fillMultiContact(listId, data, type) {
        const list = document.getElementById(listId) || document.getElementById('add-' + listId) || document.getElementById('edit-' + listId);
        if (!list) return;

        list.innerHTML = '';
        const items = Array.isArray(data) ? data : (data ? [data] : []);
        
        if (items.length > 0) {
            items.forEach(item => {
                if (type === 'email') {
                    const emailVal = typeof item === 'object' ? (item.email || item.val || '') : item;
                    addEmailRow(list.id, emailVal);
                } else {
                    // Improved Phone handling
                    let num = '', code = null;
                    if (typeof item === 'object' && item !== null) {
                        num = item.num || item.phone || item.number || '';
                        code = item.code || item.country_code || item.index || null;
                    } else {
                        num = item || '';
                    }
                    if (num) addPhoneRow(list.id, num, code);
                }
            });
        }
        
        // Ensure at least one row exists
        if (list.children.length === 0) {
            if (type === 'email') addEmailRow(list.id);
            else addPhoneRow(list.id);
        }
    }

    function filterOs(q) {
        q = q.toLowerCase();
        document.querySelectorAll('.os-opt').forEach(opt => {
            const txt = opt.textContent.toLowerCase();
            opt.classList.toggle('hidden', !txt.includes(q));
        });
    }

    document.addEventListener('click', (e) => {
        const wrap = document.querySelector('.order-select-wrap');
        if (wrap && !wrap.contains(e.target)) wrap.classList.remove('open');
    });

</script>
