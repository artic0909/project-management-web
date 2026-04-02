<!-- {{--
    admin/leads/_phone_email_assets.blade.php
    Include this at the bottom of create.blade.php and edit.blade.php
--}} -->

<style>
    .multi-row {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
    }

    .multi-row:last-child {
        margin-bottom: 0;
    }

    .phone-wrap {
        display: flex;
        flex: 1;
        min-width: 0;
        border: 1px solid var(--b1);
        border-radius: var(--r-sm);
        overflow: hidden;
    }

    .country-sel {
        border: none;
        border-right: 1px solid var(--b1);
        background: var(--bg3);
        color: var(--t2);
        padding: 6px 4px 6px 8px;
        font-size: 13px;
        cursor: pointer;
        outline: none;
        font-family: inherit;
        width: 100px;
        flex-shrink: 0;
    }

    .phone-num-inp {
        border: none;
        padding: 6px 10px;
        font-size: 14px;
        font-family: inherit;
        flex: 1;
        min-width: 0;
        outline: none;
        background: transparent;
        color: var(--t1);
    }

    .multi-email-inp {
        flex: 1;
        min-width: 0;
    }

    .row-remove-btn, .row-add-btn {
        background: none;
        border: 1px solid var(--b2);
        border-radius: var(--r-sm);
        width: 32px;
        height: 32px;
        cursor: pointer;
        color: var(--t3);
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        padding: 0;
        transition: var(--transition);
    }

    .row-remove-btn:hover {
        color: #ef4444;
        border-color: #ef4444;
        background: rgba(239, 68, 68, .08);
    }

    .row-add-btn {
        color: var(--accent);
        border-color: var(--accent);
    }

    .row-add-btn:hover {
        background: var(--accent-bg);
    }
</style>

<script>
    const COUNTRIES = [{
            f: "🇦🇫",
            n: "Afghanistan",
            c: "+93"
        }, {
            f: "🇦🇱",
            n: "Albania",
            c: "+355"
        }, {
            f: "🇩🇿",
            n: "Algeria",
            c: "+213"
        },
        {
            f: "🇦🇩",
            n: "Andorra",
            c: "+376"
        }, {
            f: "🇦🇴",
            n: "Angola",
            c: "+244"
        }, {
            f: "🇦🇷",
            n: "Argentina",
            c: "+54"
        },
        {
            f: "🇦🇺",
            n: "Australia",
            c: "+61"
        }, {
            f: "🇦🇹",
            n: "Austria",
            c: "+43"
        }, {
            f: "🇧🇩",
            n: "Bangladesh",
            c: "+880"
        },
        {
            f: "🇧🇪",
            n: "Belgium",
            c: "+32"
        }, {
            f: "🇧🇷",
            n: "Brazil",
            c: "+55"
        }, {
            f: "🇨🇦",
            n: "Canada",
            c: "+1"
        },
        {
            f: "🇨🇳",
            n: "China",
            c: "+86"
        }, {
            f: "🇨🇴",
            n: "Colombia",
            c: "+57"
        }, {
            f: "🇩🇰",
            n: "Denmark",
            c: "+45"
        },
        {
            f: "🇪🇬",
            n: "Egypt",
            c: "+20"
        }, {
            f: "🇫🇷",
            n: "France",
            c: "+33"
        }, {
            f: "🇩🇪",
            n: "Germany",
            c: "+49"
        },
        {
            f: "🇬🇭",
            n: "Ghana",
            c: "+233"
        }, {
            f: "🇬🇷",
            n: "Greece",
            c: "+30"
        }, {
            f: "🇮🇳",
            n: "India",
            c: "+91"
        },
        {
            f: "🇮🇩",
            n: "Indonesia",
            c: "+62"
        }, {
            f: "🇮🇷",
            n: "Iran",
            c: "+98"
        }, {
            f: "🇮🇶",
            n: "Iraq",
            c: "+964"
        },
        {
            f: "🇮🇪",
            n: "Ireland",
            c: "+353"
        }, {
            f: "🇮🇱",
            n: "Israel",
            c: "+972"
        }, {
            f: "🇮🇹",
            n: "Italy",
            c: "+39"
        },
        {
            f: "🇯🇵",
            n: "Japan",
            c: "+81"
        }, {
            f: "🇯🇴",
            n: "Jordan",
            c: "+962"
        }, {
            f: "🇰🇪",
            n: "Kenya",
            c: "+254"
        },
        {
            f: "🇰🇼",
            n: "Kuwait",
            c: "+965"
        }, {
            f: "🇱🇧",
            n: "Lebanon",
            c: "+961"
        }, {
            f: "🇲🇾",
            n: "Malaysia",
            c: "+60"
        },
        {
            f: "🇲🇽",
            n: "Mexico",
            c: "+52"
        }, {
            f: "🇲🇦",
            n: "Morocco",
            c: "+212"
        }, {
            f: "🇳🇵",
            n: "Nepal",
            c: "+977"
        },
        {
            f: "🇳🇱",
            n: "Netherlands",
            c: "+31"
        }, {
            f: "🇳🇿",
            n: "New Zealand",
            c: "+64"
        }, {
            f: "🇳🇬",
            n: "Nigeria",
            c: "+234"
        },
        {
            f: "🇳🇴",
            n: "Norway",
            c: "+47"
        }, {
            f: "🇴🇲",
            n: "Oman",
            c: "+968"
        }, {
            f: "🇵🇰",
            n: "Pakistan",
            c: "+92"
        },
        {
            f: "🇵🇭",
            n: "Philippines",
            c: "+63"
        }, {
            f: "🇵🇱",
            n: "Poland",
            c: "+48"
        }, {
            f: "🇵🇹",
            n: "Portugal",
            c: "+351"
        },
        {
            f: "🇶🇦",
            n: "Qatar",
            c: "+974"
        }, {
            f: "🇷🇺",
            n: "Russia",
            c: "+7"
        }, {
            f: "🇸🇦",
            n: "Saudi Arabia",
            c: "+966"
        },
        {
            f: "🇸🇬",
            n: "Singapore",
            c: "+65"
        }, {
            f: "🇿🇦",
            n: "South Africa",
            c: "+27"
        }, {
            f: "🇪🇸",
            n: "Spain",
            c: "+34"
        },
        {
            f: "🇱🇰",
            n: "Sri Lanka",
            c: "+94"
        }, {
            f: "🇸🇪",
            n: "Sweden",
            c: "+46"
        }, {
            f: "🇨🇭",
            n: "Switzerland",
            c: "+41"
        },
        {
            f: "🇹🇼",
            n: "Taiwan",
            c: "+886"
        }, {
            f: "🇹🇭",
            n: "Thailand",
            c: "+66"
        }, {
            f: "🇹🇷",
            n: "Turkey",
            c: "+90"
        },
        {
            f: "🇦🇪",
            n: "UAE",
            c: "+971"
        }, {
            f: "🇬🇧",
            n: "United Kingdom",
            c: "+44"
        }, {
            f: "🇺🇸",
            n: "USA",
            c: "+1"
        },
        {
            f: "🇻🇳",
            n: "Vietnam",
            c: "+84"
        }, {
            f: "🇿🇲",
            n: "Zambia",
            c: "+260"
        }, {
            f: "🇿🇼",
            n: "Zimbabwe",
            c: "+263"
        }
    ];
    const INDIA_IDX = COUNTRIES.findIndex(c => c.n === "India");

    function buildCountrySel(selectedIdx = null) {
        const sel = document.createElement('select');
        sel.className = 'country-sel';
        sel.name = 'country_code[]';
        COUNTRIES.forEach((c, i) => {
            const opt = document.createElement('option');
            opt.value = i;
            opt.textContent = c.f + ' ' + c.c;
            opt.title = c.n;
            sel.appendChild(opt);
        });
        sel.value = selectedIdx !== null ? selectedIdx : (INDIA_IDX >= 0 ? INDIA_IDX : 0);
        return sel;
    }

    function addPhoneRow(listId, val = '', codeIdx = null) {
        const list = document.getElementById(listId);
        const row = document.createElement('div');
        row.className = 'multi-row';

        const wrap = document.createElement('div');
        wrap.className = 'phone-wrap form-inp';
        wrap.style.cssText = 'padding:0;display:flex;align-items:center;';
        wrap.appendChild(buildCountrySel(codeIdx));

        const inp = document.createElement('input');
        inp.type = 'tel';
        inp.name = 'phone[]';
        inp.className = 'phone-num-inp';
        inp.placeholder = 'XXXXX XXXXX';
        inp.value = val;
        inp.required = true;
        wrap.appendChild(inp);
        row.appendChild(wrap);

        list.appendChild(row);
        updateButtons(listId);
    }

    function addEmailRow(listId, val = '') {
        const list = document.getElementById(listId);
        const row = document.createElement('div');
        row.className = 'multi-row';

        const inp = document.createElement('input');
        inp.type = 'email';
        inp.name = 'email[]';
        inp.className = 'form-inp multi-email-inp';
        inp.placeholder = 'email@company.com';
        inp.value = val;
        inp.required = true;
        row.appendChild(inp);

        list.appendChild(row);
        updateButtons(listId);
    }

    function updateButtons(listId) {
        const list = document.getElementById(listId);
        const rows = list.querySelectorAll('.multi-row');
        
        rows.forEach((row, i) => {
            let btn = row.querySelector('.btn-action');
            if(!btn) {
                btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn-action';
                row.appendChild(btn);
            }

            if (i === rows.length - 1) {
                btn.className = 'row-add-btn btn-action';
                btn.innerHTML = '<i class="bi bi-plus-lg"></i>';
                btn.onclick = () => {
                    if (listId.includes('email')) addEmailRow(listId);
                    else addPhoneRow(listId);
                };
            } else {
                btn.className = 'row-remove-btn btn-action';
                btn.innerHTML = '<i class="bi bi-x-lg"></i>';
                btn.onclick = () => {
                    row.remove();
                    updateButtons(listId);
                };
            }
        });
    }

    // Seed default rows on page load for whichever lists exist
    document.addEventListener('DOMContentLoaded', function() {
        ['add-email-list', 'edit-email-list'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                if (el.children.length === 0) addEmailRow(id);
                else updateButtons(id);
            }
        });
        ['add-phone-list', 'edit-phone-list'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                if (el.children.length === 0) addPhoneRow(id);
                else updateButtons(id);
            }
        });
    });
</script>