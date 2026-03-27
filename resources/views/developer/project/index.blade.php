@extends('developer.layout.app')

@section('title', 'My Projects')

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-projects">
        <div class="page-header">
            <div>
                <h1 class="page-title">My Assigned Projects</h1>
                <p class="page-desc">Overview of all active and past projects assigned to you</p>
            </div>
            <div class="header-actions">
                <form action="{{ route('developer.projects.index') }}" method="GET" class="search-form">
                    <div class="search-inp-group">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" class="search-inp" placeholder="Search projects..." value="{{ request('search') }}">
                        <button type="submit" class="btn-primary-solid sm">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="dash-grid">
            @forelse($projects as $project)
                <div class="span-4">
                    <div class="dash-card project-card">
                        <div class="card-body">
                            <div class="proj-header">
                                <span class="proj-id">#PRJ-{{ str_pad($project->id, 5, '0', STR_PAD_LEFT) }}</span>
                                <span class="proj-status {{ strtolower(str_replace(' ', '-', $project->projectStatus->name ?? 'new')) }}">{{ $project->projectStatus->name ?? 'New' }}</span>
                            </div>
                            <h3 class="proj-title" title="{{ $project->project_name }}">{{ $project->project_name }}</h3>
                            <p class="proj-company">{{ $project->company_name ?? $project->client_name }}</p>
                            
                            <div class="proj-info">
                                <div class="info-item"><i class="bi bi-calendar-event"></i> Start: {{ $project->project_start_date ? $project->project_start_date->format('d M Y') : 'N/A' }}</div>
                                <div class="info-item"><i class="bi bi-calendar-check"></i> Exp: {{ $project->expected_delivery_date ? $project->expected_delivery_date->format('d M Y') : 'N/A' }}</div>
                            </div>
                            
                            <div class="proj-footer">
                                <a href="{{ route('developer.projects.show', $project->id) }}" class="btn-ghost sm w-full">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="span-12">
                    <div class="dash-card">
                        <div class="card-body text-center" style="padding:40px;">
                            <i class="bi bi-kanban" style="font-size:48px;color:var(--t4);margin-bottom:16px;display:block;"></i>
                            <h3 style="color:var(--t2);">No projects found</h3>
                            <p style="color:var(--t4);">You don't have any projects assigned yet or matching your search.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</main>

<style>
    .project-card { transition: all 0.3s ease; border: 1px solid var(--b1); }
    .project-card:hover { transform: translateY(-4px); border-color: var(--accent); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .proj-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .proj-id { font-size: 11px; font-weight: 700; color: var(--t4); font-family: var(--mono); }
    .proj-status { font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 4px; text-transform: uppercase; }
    .proj-status.new { background: rgba(245, 158, 11, .12); color: #f59e0b; }
    .proj-status.design-phase { background: rgba(6, 182, 212, .12); color: #06b6d4; }
    .proj-status.development { background: rgba(99, 102, 241, .12); color: #6366f1; }
    .proj-status.testing { background: rgba(139, 92, 246, .12); color: #8b5cf6; }
    .proj-status.completed { background: rgba(16, 185, 129, .12); color: #10b981; }
    .proj-status.on-hold { background: rgba(100, 116, 139, .12); color: #64748b; }
    .proj-title { font-size: 16px; font-weight: 700; color: var(--t1); margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .proj-company { font-size: 13px; color: var(--t3); margin-bottom: 16px; min-height: 20px; }
    .proj-info { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; padding: 12px; background: var(--bg3); border-radius: 8px; }
    .info-item { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--t2); }
    .info-item i { color: var(--accent); }
    .w-full { width: 100%; justify-content: center; }
    
    .search-inp-group { display: flex; align-items: center; background: var(--bg3); border: 1px solid var(--b1); border-radius: 8px; padding-left: 12px; }
    .search-inp { background: none; border: none; outline: none; padding: 10px; color: var(--t1); font-size: 14px; width: 250px; }
</style>
@endsection
