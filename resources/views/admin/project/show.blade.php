@extends('admin.layout.app')

@section('title', 'Project Details - ' . $project->project_name)

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-project-show">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                    @php
                        $statusClass = strtolower(str_replace(' ', '-', $project->project_status ?? 'new'));
                    @endphp
                    <span class="proj-status {{ $statusClass }}">{{ $project->project_status ?? 'New' }}</span>
                    <span style="font-size:12px;color:var(--t4);font-weight:500;">#PRJ-{{ str_pad($project->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <h1 class="page-title">{{ $project->project_name }}</h1>
                <p class="page-desc">{{ $project->company_name ?? 'Client: ' . $project->client_name }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn-primary-solid sm">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
                <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-ghost sm" style="color:#ef4444;">
                        <i class="bi bi-trash3"></i> Delete
                    </button>
                </form>
                <a href="{{ route('admin.projects.index') }}" class="btn-ghost sm">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="dash-grid">
            
            {{-- Left Column: Project Info & Technical --}}
            <div class="span-8" style="display:flex;flex-direction:column;gap:20px;">
                
                {{-- Client & Basic Info --}}
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title">Client Information</div>
                    </div>
                    <div class="card-body">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
                            <div>
                                <div style="margin-bottom:16px;">
                                    <label style="display:block;font-size:11px;color:var(--t4);text-transform:uppercase;font-weight:700;margin-bottom:4px;">Client Name</label>
                                    <div style="font-weight:600;color:var(--t1);">{{ $project->client_name }}</div>
                                </div>
                                <div style="margin-bottom:16px;">
                                    <label style="display:block;font-size:11px;color:var(--t4);text-transform:uppercase;font-weight:700;margin-bottom:4px;">Email</label>
                                    <div style="color:var(--t2);">{{ $project->email ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <label style="display:block;font-size:11px;color:var(--t4);text-transform:uppercase;font-weight:700;margin-bottom:4px;">Phone</label>
                                    <div style="color:var(--t2);">{{ $project->phone ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div>
                                <div style="margin-bottom:16px;">
                                    <label style="display:block;font-size:11px;color:var(--t4);text-transform:uppercase;font-weight:700;margin-bottom:4px;">Company</label>
                                    <div style="color:var(--t2);">{{ $project->company_name ?? 'N/A' }}</div>
                                </div>
                                <div style="margin-bottom:0;">
                                    <label style="display:block;font-size:11px;color:var(--t4);text-transform:uppercase;font-weight:700;margin-bottom:4px;">Address</label>
                                    <div style="color:var(--t2);font-size:13px;line-height:1.5;">{{ $project->full_address ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Website Technical Details --}}
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title">Website Technical Details</div>
                    </div>
                    <div class="card-body">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
                            <div>
                                <div style="margin-bottom:16px;">
                                    <label style="display:block;font-size:11px;color:var(--t4);text-transform:uppercase;font-weight:700;margin-bottom:4px;">Domain Name</label>
                                    <div style="font-weight:600;color:var(--accent);">{{ $project->domain_name ?? 'N/A' }}</div>
                                </div>
                                <div style="margin-bottom:16px;">
                                    <label style="display:block;font-size:11px;color:var(--t4);text-transform:uppercase;font-weight:700;margin-bottom:4px;">CMS / Platform</label>
                                    <div>
                                        @if($project->cms_platform)
                                            <span class="cms-tag {{ strtolower($project->cms_platform) }}">{{ $project->cms_platform == 'other' ? $project->cms_custom : $project->cms_platform }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <label style="display:block;font-size:11px;color:var(--t4);text-transform:uppercase;font-weight:700;margin-bottom:4px;">Hosting Provider</label>
                                    <div style="color:var(--t2);">{{ $project->hosting_provider ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div>
                                <div style="margin-bottom:16px;">
                                    <label style="display:block;font-size:11px;color:var(--t4);text-transform:uppercase;font-weight:700;margin-bottom:4px;">Pages & Features</label>
                                    <div style="color:var(--t2);">{{ $project->number_of_pages ?? 'N/A' }} Pages</div>
                                    <div style="font-size:12px;color:var(--t3);margin-top:4px;">{{ $project->required_features ?? 'No features listed' }}</div>
                                </div>
                                <div>
                                    <label style="display:block;font-size:11px;color:var(--t4);text-transform:uppercase;font-weight:700;margin-bottom:4px;">Login Credentials</label>
                                    <div style="font-size:12px;background:var(--bg4);padding:8px;border-radius:6px;border:1px solid var(--b1);">
                                        <div><span style="color:var(--t4);">U:</span> {{ $project->username ?? 'N/A' }}</div>
                                        <div><span style="color:var(--t4);">P:</span> {{ $project->password ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Internal Notes & Feedback --}}
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title">Notes & Feedback</div>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom:20px;">
                            <label style="display:block;font-size:11px;color:var(--t4);text-transform:uppercase;font-weight:700;margin-bottom:8px;">Internal Notes</label>
                            <div style="padding:12px;background:var(--bg3);border:1px solid var(--b1);border-radius:8px;font-size:13px;color:var(--t2);line-height:1.6;white-space:pre-wrap;">{{ $project->internal_notes ?? 'No internal notes.' }}</div>
                        </div>
                        <div>
                            <label style="display:block;font-size:11px;color:var(--t4);text-transform:uppercase;font-weight:700;margin-bottom:8px;">Latest Client Feedback</label>
                            <div style="font-style:italic;color:var(--t3);">"{{ $project->client_feedback ?? 'No feedback recorded yet.' }}"</div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Column: Financials, Team, Dates --}}
            <div class="span-4" style="display:flex;flex-direction:column;gap:20px;">
                
                {{-- Financials --}}
                <div class="dash-card" style="background:var(--accent-bg);border:1px solid color-mix(in srgb, var(--accent) 20%, transparent);">
                    <div class="card-head">
                        <div class="card-title" style="color:var(--accent);">Financial Details</div>
                    </div>
                    <div class="card-body">
                        <div style="display:flex;flex-direction:column;gap:12px;">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:13px;color:var(--t3);">Total Price</span>
                                <span style="font-weight:700;color:var(--t1);font-size:16px;">₹ {{ number_format($project->project_price, 2) }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:13px;color:var(--t3);">Advance Paid</span>
                                <span style="font-weight:600;color:#10b981;">₹ {{ number_format($project->advance_payment, 2) }}</span>
                            </div>
                            <div style="height:1px;background:var(--b1);margin:4px 0;"></div>
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:13px;color:var(--t3);">Remaining</span>
                                <span style="font-weight:800;color:#ef4444;font-size:18px;">₹ {{ number_format($project->remaining_amount, 2) }}</span>
                            </div>
                            <div style="margin-top:8px;">
                                @php
                                    $payStatus = strtolower($project->financial_payment_status ?? 'pending');
                                    $payClass = $payStatus == 'paid' ? 'paid' : ($payStatus == 'partial' ? 'pending' : 'overdue');
                                @endphp
                                <span class="status-pill {{ $payClass }}" style="width:100%;justify-content:center;padding:8px;font-size:12px;">Payment: {{ $project->financial_payment_status ?? 'Pending' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Assigned Team --}}
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title">Assigned Developers</div>
                    </div>
                    <div class="card-body">
                        <div style="display:flex;flex-direction:column;gap:10px;">
                            @forelse($project->developers as $dev)
                                <div style="display:flex;align-items:center;gap:10px;padding:8px;background:var(--bg2);border-radius:8px;">
                                    @php
                                        $words = explode(' ', $dev->name);
                                        $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                    @endphp
                                    <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#06b6d4);color:white;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;">{{ $initials }}</div>
                                    <div style="display:flex;flex-direction:column;">
                                        <span style="font-size:13px;font-weight:600;color:var(--t1);">{{ $dev->name }}</span>
                                        <span style="font-size:11px;color:var(--t4);">{{ $dev->email }}</span>
                                    </div>
                                </div>
                            @empty
                                <div style="text-align:center;padding:20px;color:var(--t4);font-style:italic;font-size:13px;">No developers assigned.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Timeline --}}
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title">Key Dates</div>
                    </div>
                    <div class="card-body">
                        <div style="display:flex;flex-direction:column;gap:14px;">
                            <div>
                                <label style="display:block;font-size:10px;color:var(--t4);text-transform:uppercase;font-weight:700;margin-bottom:4px;">Started On</label>
                                <div style="font-size:13px;color:var(--t2);">{{ $project->project_start_date ? $project->project_start_date->format('d M, Y') : 'N/A' }}</div>
                            </div>
                            <div>
                                <label style="display:block;font-size:10px;color:var(--t4);text-transform:uppercase;font-weight:700;margin-bottom:4px;">Expected Delivery</label>
                                <div style="font-size:13px;color:var(--t2);">{{ $project->expected_delivery_date ? $project->expected_delivery_date->format('d M, Y') : 'N/A' }}</div>
                            </div>
                            <div>
                                <label style="display:block;font-size:10px;color:var(--t4);text-transform:uppercase;font-weight:700;margin-bottom:4px;">Last Updated</label>
                                <div style="font-size:13px;color:var(--t2);">{{ $project->last_update_date ? $project->last_update_date->format('d M, Y') : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</main>

<style>
    .proj-status {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .proj-status::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
    }
    .proj-status.new { background: rgba(245, 158, 11, .12); color: #f59e0b; }
    .proj-status.new::before { background: #f59e0b; }
    .proj-status.design { background: rgba(6, 182, 212, .12); color: #06b6d4; }
    .proj-status.design::before { background: #06b6d4; }
    .proj-status.development { background: rgba(99, 102, 241, .12); color: #6366f1; }
    .proj-status.development::before { background: #6366f1; }
    .proj-status.testing { background: rgba(139, 92, 246, .12); color: #8b5cf6; }
    .proj-status.testing::before { background: #8b5cf6; }
    .proj-status.completed { background: rgba(16, 185, 129, .12); color: #10b981; }
    .proj-status.completed::before { background: #10b981; }
    .proj-status.on-hold { background: rgba(100, 116, 139, .12); color: #64748b; }
    .proj-status.on-hold::before { background: #64748b; }

    .cms-tag { font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 4px; }
    .cms-tag.wordpress { background: rgba(33, 117, 155, .1); color: #21759b; }
    .cms-tag.shopify { background: rgba(150, 191, 71, .1); color: #96bf47; }
    .cms-tag.custom { background: rgba(245, 158, 11, .1); color: #f59e0b; }
</style>

@endsection