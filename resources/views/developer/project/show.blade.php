@extends('developer.layout.app')

@section('title', 'Project Details - ' . $project->project_name)

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-project-show">
        <div class="page-header">
            <div>
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                    <span class="proj-status {{ strtolower(str_replace(' ', '-', $project->projectStatus->name ?? 'new')) }}">{{ $project->projectStatus->name ?? 'New' }}</span>
                    <span style="font-size:12px;color:var(--t4);font-weight:500;">#PRJ-{{ str_pad($project->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <h1 class="page-title">{{ $project->project_name }}</h1>
                <p class="page-desc">{{ $project->company_name ?? $project->client_name }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('developer.projects.tasks', $project->id) }}" class="btn-primary-solid sm">
                    <i class="bi bi-list-check"></i> Pending Tasks
                </a>
                <a href="{{ route('developer.projects.index') }}" class="btn-ghost sm">
                    <i class="bi bi-arrow-left"></i> Back to Projects
                </a>
            </div>
        </div>

        <div class="dash-grid">
            <div class="span-8">
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-info-circle-fill" style="color:var(--accent);margin-right:8px;"></i>Project Information</div>
                    </div>
                    <div class="card-body">
                        <div class="kv-grid">
                            <div class="kv-item"><label>Platform/CMS</label><div>{{ $project->cms_platform == 'other' ? $project->cms_custom : $project->cms_platform }}</div></div>
                            <div class="kv-item"><label>Pages</label><div>{{ $project->no_of_pages }}</div></div>
                            <div class="kv-item"><label>Start Date</label><div>{{ $project->project_start_date ? $project->project_start_date->format('d M Y') : 'N/A' }}</div></div>
                            <div class="kv-item"><label>Expected Delivery</label><div>{{ $project->expected_delivery_date ? $project->expected_delivery_date->format('d M Y') : 'N/A' }}</div></div>
                        </div>
                        <div class="kv-item mt-20"><label>Required Features</label><div class="val-text">{{ $project->required_features ?? 'No specific features listed' }}</div></div>
                    </div>
                </div>
            </div>
            
            <div class="span-4" style="display:flex;flex-direction:column;gap:20px;">
               <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-link-45deg" style="color:#06b6d4;margin-right:8px;"></i>Technical Links</div>
                    </div>
                    <div class="card-body">
                         <div class="kv-item"><label>Domain Name</label><div><a href="http://{{ $project->domain_name }}" target="_blank" style="color:var(--accent)">{{ $project->domain_name ?? 'N/A' }}</a></div></div>
                         <div class="kv-item" style="margin-top:12px;"><label>Hosting Provider</label><div>{{ $project->hosting_provider ?? 'N/A' }}</div></div>
                         <div class="kv-item" style="margin-top:12px;"><label>Reference Sites</label><div class="val-text">{{ $project->reference_websites ?? 'N/A' }}</div></div>
                    </div>
               </div>
               
               <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-person-check-fill" style="color:#10b981;margin-right:8px;"></i>My Status</div>
                    </div>
                    <div class="card-body">
                        @php
                            $myTasks = $project->tasks()->whereHas('assignments', function($q) {
                                $q->where('developer_id', auth()->guard('developer')->id());
                            })->get();
                            $done = $myTasks->where('status', 'Completed')->count();
                            $total = $myTasks->count();
                            $perc = $total > 0 ? round(($done / $total) * 100) : 0;
                        @endphp
                        <div style="text-align:center;">
                            <div style="font-size:24px;font-weight:700;color:var(--t1);margin-bottom:4px;">{{ $perc }}%</div>
                            <div style="font-size:11px;color:var(--t4);text-transform:uppercase;font-weight:700;margin-bottom:12px;">Tasks Completed</div>
                            <div style="width:100%;height:8px;background:var(--bg4);border-radius:10px;overflow:hidden;">
                                <div style="width:{{ $perc }}%;height:100%;background:linear-gradient(90deg, #10b981, #34d399);"></div>
                            </div>
                            <div style="margin-top:12px;font-size:13px;color:var(--t3);">{{ $done }} / {{ $total }} Tasks Done</div>
                        </div>
                    </div>
               </div>
            </div>
        </div>
    </div>
</main>

<style>
    .kv-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .kv-item label { display: block; font-size: 11px; text-transform: uppercase; color: var(--t4); font-weight: 700; margin-bottom: 4px; }
    .kv-item div { font-size: 14px; color: var(--t2); font-weight: 500; }
    .mt-20 { margin-top: 20px; }
    .val-text { line-height: 1.6; white-space: pre-wrap; font-size: 13px !important; }
    .proj-status { font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 6px; text-transform: uppercase; }
    .proj-status.new { background: #ccfbf1; color: #115e59; }
    .proj-status.in-progress { background: #dbeafe; color: #1e40af; }
    .proj-status.completed { background: #fef9c3; color: #854d0e; }
</style>
@endsection
