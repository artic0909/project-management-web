@extends('admin.layout.app')

@section('title', 'DataFirst Corp — Followup')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-followup">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                    <a href="{{ route('admin.leads.index') }}"
                        style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--t3);transition:var(--transition);"
                        onmouseover="this.style.color='var(--accent)'"
                        onmouseout="this.style.color='var(--t3)'">
                        <i class="bi bi-arrow-left"></i> All Leads
                    </a>
                    <span style="color:var(--t4);font-size:12px;">›</span>
                    <span style="font-size:13px;font-weight:600;color:var(--t2);">DataFirst Corp</span>
                </div>
                <h1 class="page-title">Lead Followup</h1>
                <p class="page-desc">Track communication history and schedule follow-ups</p>
            </div>
        </div>

        <!-- TOP STATS ROW -->
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
            <div class="dash-card" style="padding:16px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:10px;background:rgba(239,68,68,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-fire" style="font-size:18px;color:#ef4444;"></i>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--t3);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Priority</div>
                    <div style="font-size:16px;font-weight:800;color:#ef4444;margin-top:2px;">Hot 🔥</div>
                </div>
            </div>
            <div class="dash-card" style="padding:16px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:10px;background:rgba(16,185,129,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-check2-circle" style="font-size:18px;color:#10b981;"></i>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--t3);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Status</div>
                    <div style="font-size:16px;font-weight:800;color:#10b981;margin-top:2px;">Respond</div>
                </div>
            </div>
            <div class="dash-card" style="padding:16px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:10px;background:rgba(99,102,241,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-arrow-counterclockwise" style="font-size:18px;color:#6366f1;"></i>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--t3);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Total Followups</div>
                    <div style="font-size:16px;font-weight:800;color:var(--t1);margin-top:2px;">7</div>
                </div>
            </div>
            <div class="dash-card" style="padding:16px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:10px;background:rgba(245,158,11,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-calendar-event" style="font-size:18px;color:#f59e0b;"></i>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--t3);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Last Followup</div>
                    <div style="font-size:16px;font-weight:800;color:#f59e0b;margin-top:2px;">20 Mar 2026</div>
                </div>
            </div>
        </div>

        <!-- MAIN GRID -->
        <div class="dash-grid">

            <!-- LEFT: Lead Info Card -->
            <div class="dash-card span-4" style="height:fit-content;">
                <div class="card-head" style="padding:16px 18px;">
                    <div class="card-title">Lead Information</div>
                    <span class="lead-stage hot">Hot 🔥</span>
                </div>
                <div class="card-body" style="padding:0 18px 18px;">

                    <!-- Avatar + Name -->
                    <div style="display:flex;flex-direction:column;align-items:center;padding:20px 0 18px;border-bottom:1px solid var(--b1);text-align:center;">
                        <div style="width:64px;height:64px;border-radius:18px;background:linear-gradient(135deg,#8b5cf6,#ec4899);display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:800;color:#fff;margin-bottom:12px;box-shadow:0 8px 24px rgba(139,92,246,.3);">DF</div>
                        <div style="font-size:17px;font-weight:800;color:var(--t1);letter-spacing:-.3px;">DataFirst Corp</div>
                        <div style="font-size:12px;color:var(--t3);margin-top:3px;">cto@datafirst.io</div>
                        <div style="margin-top:10px;display:flex;gap:6px;">
                            <span class="src-tag website">Website</span>
                            <span style="font-size:10.5px;font-weight:700;padding:2px 7px;border-radius:5px;background:rgba(16,185,129,.12);color:#10b981;">Website Design</span>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div style="display:flex;flex-direction:column;gap:0;margin-top:14px;">

                        <div style="display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid var(--b1);">
                            <div style="width:30px;height:30px;border-radius:8px;background:var(--bg4);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-person-fill" style="font-size:13px;color:var(--t3);"></i>
                            </div>
                            <div>
                                <div style="font-size:10.5px;color:var(--t3);font-weight:600;">Contact Person</div>
                                <div style="font-size:13px;font-weight:600;color:var(--t1);">Abhishek</div>
                            </div>
                        </div>

                        <div style="display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid var(--b1);">
                            <div style="width:30px;height:30px;border-radius:8px;background:var(--bg4);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-envelope-fill" style="font-size:13px;color:var(--t3);"></i>
                            </div>
                            <div>
                                <div style="font-size:10.5px;color:var(--t3);font-weight:600;">Email</div>
                                <div style="font-size:13px;font-weight:600;color:var(--t1);">cto@datafirst.io</div>
                            </div>
                        </div>

                        <div style="display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid var(--b1);">
                            <div style="width:30px;height:30px;border-radius:8px;background:var(--bg4);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-telephone-fill" style="font-size:13px;color:var(--t3);"></i>
                            </div>
                            <div>
                                <div style="font-size:10.5px;color:var(--t3);font-weight:600;">Phone</div>
                                <div style="font-size:13px;font-weight:600;color:var(--t1);">+91 98765 43210</div>
                            </div>
                        </div>

                        <div style="display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid var(--b1);">
                            <div style="width:30px;height:30px;border-radius:8px;background:var(--bg4);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-building" style="font-size:13px;color:var(--t3);"></i>
                            </div>
                            <div>
                                <div style="font-size:10.5px;color:var(--t3);font-weight:600;">Business Type</div>
                                <div style="font-size:13px;font-weight:600;color:var(--t1);">Technology</div>
                            </div>
                        </div>

                        <div style="display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid var(--b1);">
                            <div style="width:30px;height:30px;border-radius:8px;background:var(--bg4);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-geo-alt-fill" style="font-size:13px;color:var(--t3);"></i>
                            </div>
                            <div>
                                <div style="font-size:10.5px;color:var(--t3);font-weight:600;">Address</div>
                                <div style="font-size:13px;font-weight:600;color:var(--t1);">Mumbai, Maharashtra</div>
                            </div>
                        </div>

                        <div style="display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid var(--b1);">
                            <div style="width:30px;height:30px;border-radius:8px;background:var(--bg4);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-person-check-fill" style="font-size:13px;color:var(--t3);"></i>
                            </div>
                            <div>
                                <div style="font-size:10.5px;color:var(--t3);font-weight:600;">Assigned To</div>
                                <div style="font-size:13px;font-weight:600;color:var(--t1);">Rahul Kumar</div>
                            </div>
                        </div>

                        <div style="display:flex;align-items:center;gap:10px;padding:9px 0;">
                            <div style="width:30px;height:30px;border-radius:8px;background:var(--bg4);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-calendar3" style="font-size:13px;color:var(--t3);"></i>
                            </div>
                            <div>
                                <div style="font-size:10.5px;color:var(--t3);font-weight:600;">Created Date</div>
                                <div style="font-size:13px;font-weight:600;color:var(--t1);">12 Feb 2026</div>
                            </div>
                        </div>

                    </div>

                    <!-- Quick Actions -->
                    <div style="margin-top:16px;display:flex;flex-direction:column;gap:8px;">
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--t3);margin-bottom:2px;">Quick Update</div>

                        <div class="form-row" style="margin-bottom:0;">
                            <label class="form-lbl">Change Priority</label>
                            <select class="form-inp">
                                <option selected>Hot 🔥</option>
                                <option>Warm</option>
                                <option>Cold</option>
                                <option>Lost</option>
                            </select>
                        </div>

                        <div class="form-row" style="margin-bottom:0;">
                            <label class="form-lbl">Change Status</label>
                            <select class="form-inp">
                                <option>Not Responding</option>
                                <option>Not Interested</option>
                                <option>Not Required</option>
                                <option>Location Issue</option>
                                <option>Job</option>
                                <option>Not Inquired</option>
                                <option selected>Respond</option>
                                <option>Interested</option>
                                <option>Language Barrier</option>
                                <option>Booked</option>
                                <option>Budget Issue</option>
                            </select>
                        </div>

                        <div class="form-row" style="margin-bottom:0;">
                            <label class="form-lbl">Convert Lead To</label>
                            <select class="form-inp">
                                <option>— Select —</option>
                                <option>Order</option>
                                <option>Closed</option>
                            </select>
                        </div>

                        <button class="btn-primary-solid" style="width:100%;justify-content:center;margin-top:4px;"
                            onclick="showToast('success','Lead updated!','bi-person-check-fill')">
                            <i class="bi bi-check2-circle"></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Followup History + Add Form -->
            <div class="span-8" style="display:flex;flex-direction:column;gap:16px;">

                <!-- Add Followup Card -->
                <div class="dash-card">
                    <div class="card-head" style="padding:16px 18px;">
                        <div>
                            <div class="card-title">Add New Followup</div>
                            <div class="card-sub">Schedule a call or message follow-up</div>
                        </div>
                    </div>
                    <div class="card-body" style="padding:14px 18px 18px;">
                        <div class="form-grid">
                            <div class="form-row">
                                <label class="form-lbl">Followup Date *</label>
                                <input type="date" class="form-inp" id="followupDateMain">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Followup Type</label>
                                <select class="form-inp">
                                    <option>Calling</option>
                                    <option>Message</option>
                                    <option>Both</option>
                                </select>
                            </div>
                            <div class="form-row" style="grid-column:1/-1;">
                                <label class="form-lbl">Calling Note</label>
                                <textarea class="form-inp" rows="2" placeholder="Add calling follow-up note…"></textarea>
                            </div>
                            <div class="form-row" style="grid-column:1/-1;margin-bottom:0;">
                                <label class="form-lbl">Message Note</label>
                                <textarea class="form-inp" rows="2" placeholder="Add message follow-up note…"></textarea>
                            </div>
                        </div>
                        <div style="display:flex;justify-content:flex-end;margin-top:14px;">
                            <button class="btn-primary-solid"
                                onclick="showToast('success','Followup Added!','bi-arrow-counterclockwise')">
                                <i class="bi bi-plus-lg"></i> Add Followup
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Followup History Card -->
                <div class="dash-card">
                    <div class="card-head" style="padding:16px 18px 12px;">
                        <div>
                            <div class="card-title">Followup History</div>
                            <div class="card-sub">7 total follow-ups recorded</div>
                        </div>
                        <div class="card-actions">
                            <select class="filter-select">
                                <option>All Types</option>
                                <option>Calling</option>
                                <option>Message</option>
                                <option>Both</option>
                            </select>
                        </div>
                    </div>
                    <div style="padding:6px 18px 18px;display:flex;flex-direction:column;gap:10px;">

                        <!-- Timeline line -->
                        <div style="position:relative;padding-left:28px;">
                            <div style="position:absolute;left:11px;top:0;bottom:0;width:2px;background:var(--b1);border-radius:2px;"></div>

                            <!-- Followup Item: Both -->
                            <div style="position:relative;margin-bottom:12px;">
                                <div style="position:absolute;left:-22px;top:16px;width:14px;height:14px;border-radius:50%;background:var(--bg2);border:2px solid #6366f1;display:flex;align-items:center;justify-content:center;">
                                    <div style="width:5px;height:5px;border-radius:50%;background:#6366f1;"></div>
                                </div>
                                <div style="background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r);overflow:hidden;">
                                    <div style="padding:12px 14px 10px;display:flex;align-items:center;justify-content:between;border-bottom:1px solid var(--b1);">
                                        <div style="display:flex;align-items:center;gap:8px;flex:1;">
                                            <div style="display:flex;align-items:center;gap:5px;background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.2);border-radius:6px;padding:5px 10px;">
                                                <i class="bi bi-clock" style="font-size:11px;color:#6366f1;"></i>
                                                <span style="font-size:12px;font-weight:700;color:#6366f1;">20 Mar 2026</span>
                                            </div>
                                            <span style="font-size:11px;color:var(--t3);">
                                                <i class="bi bi-telephone-fill" style="color:#10b981;"></i>/<i class="bi bi-chat-fill" style="color:#f59e0b;"></i>
                                                &nbsp;Both
                                            </span>
                                            <span style="font-size:10px;color:var(--t4);margin-left:auto;">Next scheduled</span>
                                        </div>
                                    </div>
                                    <div style="padding:12px 14px;display:flex;flex-direction:column;gap:10px;">
                                        <div style="border-left:3px solid #10b981;padding-left:10px;">
                                            <p style="font-size:10.5px;font-weight:700;color:var(--t3);margin:0 0 3px;text-transform:uppercase;letter-spacing:.05em;">Calling Note</p>
                                            <p style="font-size:13px;color:var(--t2);margin:0;line-height:1.6;">Discussed project requirements. Client is very interested in redesigning their data portal. Schedule a demo call next week.</p>
                                        </div>
                                        <div style="border-left:3px solid #f59e0b;padding-left:10px;">
                                            <p style="font-size:10.5px;font-weight:700;color:var(--t3);margin:0 0 3px;text-transform:uppercase;letter-spacing:.05em;">Message Note</p>
                                            <p style="font-size:13px;color:var(--t2);margin:0;line-height:1.6;">Sent proposal doc via WhatsApp. Awaiting confirmation from CTO.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Followup Item: Calling only -->
                            <div style="position:relative;margin-bottom:12px;">
                                <div style="position:absolute;left:-22px;top:16px;width:14px;height:14px;border-radius:50%;background:var(--bg2);border:2px solid #10b981;display:flex;align-items:center;justify-content:center;">
                                    <div style="width:5px;height:5px;border-radius:50%;background:#10b981;"></div>
                                </div>
                                <div style="background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r);overflow:hidden;">
                                    <div style="padding:12px 14px 10px;border-bottom:1px solid var(--b1);display:flex;align-items:center;gap:8px;">
                                        <div style="display:flex;align-items:center;gap:5px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.2);border-radius:6px;padding:5px 10px;">
                                            <i class="bi bi-clock" style="font-size:11px;color:#10b981;"></i>
                                            <span style="font-size:12px;font-weight:700;color:#10b981;">15 Mar 2026</span>
                                        </div>
                                        <span style="font-size:11px;color:var(--t3);"><i class="bi bi-telephone-fill" style="color:#10b981;"></i>&nbsp;Calling</span>
                                    </div>
                                    <div style="padding:12px 14px;">
                                        <div style="border-left:3px solid #10b981;padding-left:10px;">
                                            <p style="font-size:10.5px;font-weight:700;color:var(--t3);margin:0 0 3px;text-transform:uppercase;letter-spacing:.05em;">Calling Note</p>
                                            <p style="font-size:13px;color:var(--t2);margin:0;line-height:1.6;">Brief call. Client confirmed they are reviewing the proposal internally. Follow up in 5 days.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Followup Item: Message only -->
                            <div style="position:relative;margin-bottom:12px;">
                                <div style="position:absolute;left:-22px;top:16px;width:14px;height:14px;border-radius:50%;background:var(--bg2);border:2px solid #f59e0b;display:flex;align-items:center;justify-content:center;">
                                    <div style="width:5px;height:5px;border-radius:50%;background:#f59e0b;"></div>
                                </div>
                                <div style="background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r);overflow:hidden;">
                                    <div style="padding:12px 14px 10px;border-bottom:1px solid var(--b1);display:flex;align-items:center;gap:8px;">
                                        <div style="display:flex;align-items:center;gap:5px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.2);border-radius:6px;padding:5px 10px;">
                                            <i class="bi bi-clock" style="font-size:11px;color:#f59e0b;"></i>
                                            <span style="font-size:12px;font-weight:700;color:#f59e0b;">10 Mar 2026</span>
                                        </div>
                                        <span style="font-size:11px;color:var(--t3);"><i class="bi bi-chat-fill" style="color:#f59e0b;"></i>&nbsp;Message</span>
                                    </div>
                                    <div style="padding:12px 14px;">
                                        <div style="border-left:3px solid #f59e0b;padding-left:10px;">
                                            <p style="font-size:10.5px;font-weight:700;color:var(--t3);margin:0 0 3px;text-transform:uppercase;letter-spacing:.05em;">Message Note</p>
                                            <p style="font-size:13px;color:var(--t2);margin:0;line-height:1.6;">Sent a WhatsApp message checking in. Client replied they are busy and will respond next week.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Followup Item: Both older -->
                            <div style="position:relative;margin-bottom:12px;">
                                <div style="position:absolute;left:-22px;top:16px;width:14px;height:14px;border-radius:50%;background:var(--bg2);border:2px solid #8b5cf6;display:flex;align-items:center;justify-content:center;">
                                    <div style="width:5px;height:5px;border-radius:50%;background:#8b5cf6;"></div>
                                </div>
                                <div style="background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r);overflow:hidden;">
                                    <div style="padding:12px 14px 10px;border-bottom:1px solid var(--b1);display:flex;align-items:center;gap:8px;">
                                        <div style="display:flex;align-items:center;gap:5px;background:rgba(139,92,246,.1);border:1px solid rgba(139,92,246,.2);border-radius:6px;padding:5px 10px;">
                                            <i class="bi bi-clock" style="font-size:11px;color:#8b5cf6;"></i>
                                            <span style="font-size:12px;font-weight:700;color:#8b5cf6;">02 Mar 2026</span>
                                        </div>
                                        <span style="font-size:11px;color:var(--t3);">
                                            <i class="bi bi-telephone-fill" style="color:#10b981;"></i>/<i class="bi bi-chat-fill" style="color:#f59e0b;"></i>
                                            &nbsp;Both
                                        </span>
                                    </div>
                                    <div style="padding:12px 14px;display:flex;flex-direction:column;gap:10px;">
                                        <div style="border-left:3px solid #10b981;padding-left:10px;">
                                            <p style="font-size:10.5px;font-weight:700;color:var(--t3);margin:0 0 3px;text-transform:uppercase;letter-spacing:.05em;">Calling Note</p>
                                            <p style="font-size:13px;color:var(--t2);margin:0;line-height:1.6;">Initial discovery call. Understood requirements for website redesign. Client has a budget constraint but open to phased delivery.</p>
                                        </div>
                                        <div style="border-left:3px solid #f59e0b;padding-left:10px;">
                                            <p style="font-size:10.5px;font-weight:700;color:var(--t3);margin:0 0 3px;text-transform:uppercase;letter-spacing:.05em;">Message Note</p>
                                            <p style="font-size:13px;color:var(--t2);margin:0;line-height:1.6;">Shared company portfolio and case studies over email. Client acknowledged receipt.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Older items collapsed -->
                            <div style="position:relative;margin-bottom:12px;">
                                <div style="position:absolute;left:-22px;top:16px;width:14px;height:14px;border-radius:50%;background:var(--bg2);border:2px solid var(--b3);display:flex;align-items:center;justify-content:center;">
                                    <div style="width:5px;height:5px;border-radius:50%;background:var(--t4);"></div>
                                </div>
                                <div style="background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r);overflow:hidden;">
                                    <div style="padding:12px 14px 10px;border-bottom:1px solid var(--b1);display:flex;align-items:center;gap:8px;">
                                        <div style="display:flex;align-items:center;gap:5px;background:var(--bg4);border:1px solid var(--b1);border-radius:6px;padding:5px 10px;">
                                            <i class="bi bi-clock" style="font-size:11px;color:var(--t3);"></i>
                                            <span style="font-size:12px;font-weight:700;color:var(--t3);">22 Feb 2026</span>
                                        </div>
                                        <span style="font-size:11px;color:var(--t3);"><i class="bi bi-telephone-fill" style="color:#10b981;"></i>&nbsp;Calling</span>
                                    </div>
                                    <div style="padding:12px 14px;">
                                        <div style="border-left:3px solid var(--b3);padding-left:10px;">
                                            <p style="font-size:10.5px;font-weight:700;color:var(--t3);margin:0 0 3px;text-transform:uppercase;letter-spacing:.05em;">Calling Note</p>
                                            <p style="font-size:13px;color:var(--t2);margin:0;line-height:1.6;">No answer. Left a voicemail requesting callback.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="position:relative;margin-bottom:12px;">
                                <div style="position:absolute;left:-22px;top:16px;width:14px;height:14px;border-radius:50%;background:var(--bg2);border:2px solid var(--b3);display:flex;align-items:center;justify-content:center;">
                                    <div style="width:5px;height:5px;border-radius:50%;background:var(--t4);"></div>
                                </div>
                                <div style="background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r);overflow:hidden;">
                                    <div style="padding:12px 14px 10px;border-bottom:1px solid var(--b1);display:flex;align-items:center;gap:8px;">
                                        <div style="display:flex;align-items:center;gap:5px;background:var(--bg4);border:1px solid var(--b1);border-radius:6px;padding:5px 10px;">
                                            <i class="bi bi-clock" style="font-size:11px;color:var(--t3);"></i>
                                            <span style="font-size:12px;font-weight:700;color:var(--t3);">14 Feb 2026</span>
                                        </div>
                                        <span style="font-size:11px;color:var(--t3);"><i class="bi bi-chat-fill" style="color:#f59e0b;"></i>&nbsp;Message</span>
                                    </div>
                                    <div style="padding:12px 14px;">
                                        <div style="border-left:3px solid var(--b3);padding-left:10px;">
                                            <p style="font-size:10.5px;font-weight:700;color:var(--t3);margin:0 0 3px;text-transform:uppercase;letter-spacing:.05em;">Message Note</p>
                                            <p style="font-size:13px;color:var(--t2);margin:0;line-height:1.6;">First contact via LinkedIn. Client showed interest in our services and asked to connect on call.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lead Created marker -->
                            <div style="position:relative;">
                                <div style="position:absolute;left:-25px;top:6px;width:20px;height:20px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-star-fill" style="font-size:9px;color:#fff;"></i>
                                </div>
                                <div style="padding-left:4px;padding-top:4px;">
                                    <span style="font-size:12px;color:var(--t3);font-weight:500;">Lead created on <strong style="color:var(--t2);">12 Feb 2026</strong></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div><!-- end span-8 -->
        </div><!-- end dash-grid -->
    </div>
</main>

@endsection