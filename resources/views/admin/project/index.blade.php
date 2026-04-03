@extends('admin.layout.app')

@section('title', 'All Projects')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-projects">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">All Projects</h1>
                <p class="page-desc">Manage website and development projects</p>
            </div>
            <div class="header-actions">
                <button class="btn-primary-solid sm">
                    <i class="bi bi-file-earmark-plus-fill"></i> Import
                </button>
                <button class="btn-primary-solid sm">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Export
                </button>
                <a href="{{ route($routePrefix . '.projects.create') }}" class="btn-primary-solid">
                    <i class="bi bi-plus-lg"></i> Add Project
                </a>
            </div>
        </div>

        {{-- ── KPI CARDS ── --}}
        <div id="statsWrap" style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;">

            <div class="dash-card {{ !request()->has('q') && !request()->has('project_status_id') && !request()->has('start_date') ? 'active' : '' }}" 
                style="padding:16px 18px;cursor:pointer;" onclick="window.location.href='{{ route($routePrefix . '.projects.index') }}'">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(99,102,241,.13);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-kanban-fill" style="font-size:17px;color:#6366f1;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(99,102,241,.1);color:#818cf8;">Total</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">{{ $totalProjects }}</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">Total Projects</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:100%;background:#6366f1;border-radius:3px;"></div>
                </div>
            </div>

            @php 
                $activeStatus = $statuses['project_statuses']->where('name', 'development')->first(); 
            @endphp
            <div class="dash-card {{ request('project_status_id') == ($activeStatus->id ?? 'xxx') ? 'active' : '' }}" 
                style="padding:16px 18px;cursor:pointer;" onclick="window.location.href='{{ route($routePrefix . '.projects.index', ['project_status_id' => ($activeStatus->id ?? '')]) }}'">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(6,182,212,.13);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-play-circle-fill" style="font-size:17px;color:#06b6d4;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(6,182,212,.1);color:#06b6d4;">Active</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">{{ $activeProjects }}</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">In Progress</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:{{ $totalProjects > 0 ? ($activeProjects / $totalProjects) * 100 : 0 }}%;background:#06b6d4;border-radius:3px;"></div>
                </div>
            </div>

            @php 
                $doneStatus = $statuses['project_statuses']->where('name', 'complete')->first(); 
            @endphp
            <div class="dash-card {{ request('project_status_id') == ($doneStatus->id ?? 'xxx') ? 'active' : '' }}" 
                style="padding:16px 18px;cursor:pointer;" onclick="window.location.href='{{ route($routePrefix . '.projects.index', ['project_status_id' => ($doneStatus->id ?? '')]) }}'">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(16,185,129,.13);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-check-circle-fill" style="font-size:17px;color:#10b981;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(16,185,129,.1);color:#10b981;">Done</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">{{ $completedProjects }}</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">Completed</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:{{ $totalProjects > 0 ? ($completedProjects / $totalProjects) * 100 : 0 }}%;background:#10b981;border-radius:3px;"></div>
                </div>
            </div>

            @php 
                $holdStatus = $statuses['project_statuses']->where('name', 'on hold')->first(); 
            @endphp
            <div class="dash-card {{ request('project_status_id') == ($holdStatus->id ?? 'xxx') ? 'active' : '' }}" 
                style="padding:16px 18px;cursor:pointer;" onclick="window.location.href='{{ route($routePrefix . '.projects.index', ['project_status_id' => ($holdStatus->id ?? '')]) }}'">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(245,158,11,.13);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-pause-circle-fill" style="font-size:17px;color:#f59e0b;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(245,158,11,.1);color:#f59e0b;">Hold</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">{{ $onHoldProjects }}</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">On Hold</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:{{ $totalProjects > 0 ? ($onHoldProjects / $totalProjects) * 100 : 0 }}%;background:#f59e0b;border-radius:3px;"></div>
                </div>
            </div>

        </div>

        {{-- ── TABLE ── --}}
        <div class="dash-grid" id="tableWrap">
            <div class="dash-card span-12">
                <div class="card-head">
                    <div>
                        <div class="card-title">Project Pipeline</div>
                        <div class="card-sub" id="projectTableSub">{{ $projects->total() }} total projects</div>
                    </div>
                    <div class="card-actions mb-2">
                        <form action="{{ route($routePrefix . '.projects.index') }}" method="GET" class="card-actions mb-0">
                            <div class="global-search">
                                <i class="bi bi-search"></i>
                                <input type="text" name="q" id="searchQuery" value="{{ request('q') }}" placeholder="Search projects...">
                                <button type="submit" class="btn-primary-solid sm" style="display:none;">Search</button>
                            </div>

                            <!-- ══ DATE RANGE PICKER TRIGGER ══ -->
                            <button type="button" id="dateRangeTrigger" class="drp-trigger" onclick="toggleDatePicker()">
                                <i class="bi bi-calendar3"></i>
                                <span id="drpLabel">{{ request('start_date') ? request('start_date') . ' - ' . request('end_date') : 'All Time' }}</span>
                                <i class="bi bi-chevron-down drp-chevron" id="drpChevron"></i>
                            </button>

                            <!-- Hidden inputs for date range -->
                            <input type="hidden" name="start_date" id="drpStartInput" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" id="drpEndInput" value="{{ request('end_date') }}">

                            <select name="project_status_id" class="filter-select" onchange="updateFilters()">
                                <option value="">All Statuses</option>
                                @foreach($statuses['project_statuses'] as $s)
                                    <option value="{{ $s->id }}" {{ request('project_status_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>

                            <!-- <select name="payment_status_id" class="filter-select" onchange="updateFilters()">
                                <option value="">All Payments</option>
                                @foreach($statuses['payment_statuses'] as $s)
                                    <option value="{{ $s->id }}" {{ request('payment_status_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select> -->

                            <select name="service_id" class="filter-select" onchange="updateFilters()">
                                <option value="">Services</option>
                                @foreach($allServices as $service)
                                    <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                                @endforeach
                            </select>

                            <select name="source_id" class="filter-select" onchange="updateFilters()">
                                <option value="">Sources</option>
                                @foreach($allSources as $source)
                                    <option value="{{ $source->id }}" {{ request('source_id') == $source->id ? 'selected' : '' }}>{{ $source->name }}</option>
                                @endforeach
                            </select>

                            <select name="assigned_to" class="filter-select" onchange="updateFilters()">
                                <option value="">Developers</option>
                                @foreach($allDevelopers as $dev)
                                    <option value="{{ $dev->id }}" {{ request('assigned_to') == $dev->id ? 'selected' : '' }}>{{ $dev->name }}</option>
                                @endforeach
                            </select>

                            <select name="sales_person_id" class="filter-select" onchange="updateFilters()">
                                <option value="">Sales Person</option>
                                @foreach($allSales as $sale)
                                    <option value="{{ $sale->id }}" {{ request('sales_person_id') == $sale->id ? 'selected' : '' }}>{{ $sale->name }}</option>
                                @endforeach
                            </select>
                        </form>

                        <div style="position:relative;">
                            @include('admin.includes.date-range-picker')
                        </div>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table" id="projectsTable">
                        <thead>
                            <tr>
                                <th>Project ID</th>
                                <th>Project / Domain</th>
                                <th>Client</th>
                                <th>CMS</th>
                                <th>Order Date</th>
                                <th>Start Date</th>
                                <th>Delivery</th>
                                <th>Services</th>
                                <!-- <th>Sources</th> -->
                                <th>Assigned To</th>
                                <th>Sales Person</th>
                                <th>Project Status</th>
                                <!-- <th>Project Price</th> -->
                                <!-- <th>Advance</th> -->
                                <!-- <th>Remaining</th> -->
                                <!-- <th>Payment</th> -->
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td><span class="mono">#PRJ-{{ str_pad($project->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                                <td>
                                    <div class="lead-cell">
                                        @php
                                            $nameParts = explode('.', $project->project_name);
                                            $initials = strtoupper(substr($nameParts[0], 0, 2));
                                        @endphp
                                        <div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">{{ $initials }}</div>
                                        <div>
                                            <div class="ln">{{ $project->project_name }}</div>
                                            <div class="ls">{{ $project->company_name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ln">{{ $project->client_name }}</div>
                                    <div class="ls">
                                        @if(is_array($project->phones) && count($project->phones) > 0)
                                            {{ is_array($project->phones[0]) ? $project->phones[0]['num'] : $project->phones[0] }}
                                            @if(count($project->phones) > 1) <small class="text-muted">(+{{ count($project->phones)-1 }})</small> @endif
                                        @elseif($project->phones)
                                            {{ $project->phones }}
                                        @else
                                            {{ is_array($project->emails) && count($project->emails) > 0 ? $project->emails[0] : ($project->emails ?? 'N/A') }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($project->cms_platform)
                                        <span class="cms-tag {{ strtolower($project->cms_platform) }}">{{ $project->cms_platform == 'other' ? $project->cms_custom : $project->cms_platform }}</span>
                                    @else
                                        <span style="color:var(--t4);font-size:11px;">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="ls">{{ $project->order_date_create ? $project->order_date_create->format('d M Y') : 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="ls">{{ $project->project_start_date ? $project->project_start_date->format('d M Y') : 'N/A' }}</div>
                                </td>
                                <td>
                                    @if($project->actual_delivery_date)
                                        <span class="date-cell {{ $project->actual_delivery_date->isPast() ? 'danger' : 'warn' }}">
                                            {{ $project->actual_delivery_date->format('d M Y') }}
                                        </span>
                                    @else
                                        <span style="color:var(--t4);font-size:11px;">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex;flex-wrap:wrap;gap:4px;max-width:200px;">
                                        @forelse($project->services as $service)
                                            <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:12px;background:rgba(99,102,241,0.1);color:#6366f1;border:1px solid rgba(99,102,241,0.2);white-space:nowrap;">
                                                {{ $service->name }}
                                            </span>
                                        @empty
                                            <span style="color:var(--t4);font-size:11px;">—</span>
                                        @endforelse
                                    </div>
                                </td>
                                <!-- <td>
                                    <div style="display:flex;flex-wrap:wrap;gap:4px;max-width:180px;">
                                        @forelse($project->sources as $source)
                                            <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:12px;background:rgba(16,185,129,0.1);color:#10b981;border:1px solid rgba(16,185,129,0.2);white-space:nowrap;">
                                                {{ $source->name }}
                                            </span>
                                        @empty
                                            <span style="color:var(--t4);font-size:11px;">—</span>
                                        @endforelse
                                    </div>
                                </td> -->
                                <td>
                                    <div style="display:flex; flex-direction:column; gap:4px;">
                                        @forelse($project->developers as $dev)
                                            <div style="font-size:12px; white-space:nowrap;">
                                                <span style="font-weight:600;color:var(--t1);">{{ $dev->name }} - {{ $dev->email }}</span>
                                                @if($dev->designation)
                                                    <span style="font-size:10px;color:var(--t3);"> - {{ $dev->designation }}</span>
                                                @endif
                                            </div>
                                        @empty
                                            <span style="color:var(--t4);font-size:11px;">Unassigned</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td>
                                    <div style="display:flex; flex-direction:column; gap:4px;">
                                        @forelse($project->salesPersons as $sale)
                                            <div style="font-size:12px; white-space:nowrap;">
                                                <span style="font-weight:600;color:var(--t1);">{{ $sale->name }}-{{ $sale->email }}</span>
                                            </div>
                                        @empty
                                            <span style="color:var(--t4);font-size:11px;">Unassigned</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $displayProjStatus = $project->projectStatus ? $project->projectStatus->name : ($project->project_status ?? 'New');
                                        $statusClass = strtolower(str_replace(' ', '-', $displayProjStatus));
                                    @endphp
                                    <span class="proj-status {{ $statusClass }}">{{ $displayProjStatus }}</span>
                                </td>
                                <!-- <td><span class="money-cell">₹{{ number_format($project->project_price, 0) }}</span></td>
                                <td><span class="money-cell" style="color:#10b981;">₹{{ number_format($project->advance_payment, 0) }}</span></td>
                                <td><span class="money-cell" style="color:#ef4444;">₹{{ number_format($project->remaining_amount, 0) }}</span></td>
                                <td>
                                    @php
                                        $displayPayStatus = $project->paymentStatus ? $project->paymentStatus->name : ($project->payment_status ?? 'N/A');
                                        $payClass = strtolower(str_replace(' ', '-', $displayPayStatus));
                                    @endphp
                                    <span class="status-pill {{ $payClass }}">{{ $displayPayStatus }}</span>
                                </td> -->
                                <td>
                                    @if($project->createdBy)
                                        <div class="ln">{{ $project->createdBy->name }}</div>
                                        <div class="ls" style="font-size:10px">{{ $project->createdBy->email }}</div>
                                    @else
                                        <div class="ln">System</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="row-actions">
                                        @php
                                            $codes = [0=>'+93',1=>'+355',2=>'+213',3=>'+376',4=>'+244',5=>'+54',6=>'+61',7=>'+43',8=>'+880',9=>'+32',10=>'+55',11=>'+1',12=>'+86',13=>'+57',14=>'+45',15=>'+20',16=>'+33',17=>'+49',18=>'+233',19=>'+30',20=>'+91',21=>'+62',22=>'+98',23=>'+964',24=>'+353',25=>'+972',26=>'+39',27=>'+81',28=>'+962',29=>'+254',30=>'+965',31=>'+961',32=>'+60',33=>'+52',34=>'+212',35=>'+977',36=>'+31',37=>'+64',38=>'+234',39=>'+47',40=>'+968',41=>'+92',42=>'+63',43=>'+48',44=>'+351',45=>'+974',46=>'+7',47=>'+966',48=>'+65',49=>'+27',50=>'+34',51=>'+94',52=>'+46',53=>'+41',54=>'+886',55=>'+66',56=>'+90',57=>'+971',58=>'+44',59=>'+1',60=>'+84',61=>'+260',62=>'+263'];
                                            $phoneList = is_array($project->phones) ? $project->phones : [];
                                            $emailList = is_array($project->emails) ? $project->emails : [];
                                            $fullPhones = [];
                                            foreach($phoneList as $p) {
                                                $fullPhones[] = (isset($p['code_idx']) && isset($codes[$p['code_idx']])) ? ($codes[$p['code_idx']] . ($p['num'] ?? '')) : (is_array($p) ? ($p['num'] ?? '') : $p);
                                            }
                                        @endphp
                                        <style>
                                            .ra-btn.phone:hover {
                                                background: rgba(16, 185, 129, 0.1) !important;
                                                color: #10b981 !important;
                                                border-color: #10b981 !important;
                                            }
                                        </style>
                                        <a href="javascript:void(0)" class="ra-btn phone" 
                                           onclick="handleContactClick(event, 'tel', {{ json_encode($fullPhones) }})" title="Call Client">
                                            <i class="bi bi-telephone-fill"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="ra-btn email" 
                                           onclick="handleContactClick(event, 'mailto', {{ json_encode($emailList) }})" title="Email Client">
                                            <i class="bi bi-envelope-fill"></i>
                                        </a>

                                        <a href="{{ route($routePrefix . '.projects.tasks', $project->id) }}" class="ra-btn" title="Tasks" style="color:#6366f1;background:rgba(99,102,241,0.1);"><i class="bi bi-list-task"></i></a>
                                        <a href="{{ route($routePrefix . '.projects.show', $project->id) }}" class="ra-btn" title="View"><i class="bi bi-eye-fill"></i></a>
                                        <a href="{{ route($routePrefix . '.payments.create', $project->order_id) }}" class="ra-btn" title="Payments"><i class="bi bi-wallet2"></i></a>
                                        <a href="{{ route($routePrefix . '.projects.edit', $project->id) }}" class="ra-btn" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                                        <form action="{{ route($routePrefix . '.projects.destroy', $project->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this project?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ra-btn danger" title="Delete"><i class="bi bi-trash-fill"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" style="text-align:center;padding:40px;color:var(--t4);">No projects found matching your criteria.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span class="tf-info" id="projCount">Showing {{ $projects->firstItem() ?? 0 }} to {{ $projects->lastItem() ?? 0 }} of {{ $projects->total() }} Projects</span>
                    <div class="tf-pagination">
                        {{ $projects->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ── EDIT MODAL ── --}}
    <div class="modal-backdrop" id="editProjectModal">
        <div class="modal-box modal-box-lg" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <span>Edit Project</span>
                <button class="modal-close" onclick="closeModal('editProjectModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd">

                {{-- Basic Info --}}
                <div class="proj-section-lbl"><i class="bi bi-info-circle-fill"></i> Basic Information</div>
                <div class="form-grid" style="margin-bottom:0;">
                    <div class="form-row"><label class="form-lbl">Project Name / Domain *</label><input type="text" class="form-inp" placeholder="e.g. novatech.io"></div>
                    <div class="form-row"><label class="form-lbl">Client Name *</label><input type="text" class="form-inp" placeholder="Full name"></div>
                    <div class="form-row"><label class="form-lbl">Email</label><input type="email" class="form-inp" placeholder="email@company.com"></div>
                    <div class="form-row"><label class="form-lbl">Phone</label><input type="tel" class="form-inp" placeholder="+91 XXXXX XXXXX"></div>
                    <div class="form-row"><label class="form-lbl">Company Name</label><input type="text" class="form-inp" placeholder="Company name"></div>
                    <div class="form-row"><label class="form-lbl">Starting Date</label><input type="date" class="form-inp"></div>
                    <div class="form-row"><label class="form-lbl">Plan Name</label><input type="text" class="form-inp" placeholder="e.g. dynamick"></div>
                    <div class="form-row">
                        <label class="form-lbl">Payment Status</label>
                        <select class="form-inp">
                            <option>Pending</option>
                            <option>Partial</option>
                            <option>Paid</option>
                        </select>
                    </div>
                    <div class="form-row"><label class="form-lbl">Username</label><input type="text" class="form-inp" placeholder="Account username"></div>
                    <div class="form-row"><label class="form-lbl">Password</label><input type="text" class="form-inp" placeholder="Account password"></div>
                    <div class="form-row"><label class="form-lbl">No. of Mail IDs</label><input type="number" class="form-inp" placeholder="e.g. 5"></div>
                    <div class="form-row"><label class="form-lbl">Mail Password</label><input type="text" class="form-inp" placeholder="Mail password"></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Domain, Server Book</label><input type="text" class="form-inp" placeholder="Domain & server info"></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Full Address</label><textarea class="form-inp" rows="2" placeholder="Full address…"></textarea></div>
                </div>

                {{-- Website Project Details --}}
                <div class="proj-section-lbl" style="margin-top:18px;"><i class="bi bi-globe2"></i> Website Project Details</div>
                <div class="form-grid" style="margin-bottom:0;">
                    <div class="form-row"><label class="form-lbl">Domain Name</label><input type="text" class="form-inp" placeholder="example.com"></div>
                    <div class="form-row"><label class="form-lbl">Hosting Provider</label><input type="text" class="form-inp" placeholder="Hostinger, GoDaddy…"></div>
                    <div class="form-row">
                        <label class="form-lbl">CMS / Platform</label>
                        <select class="form-inp">
                            <option>WordPress</option>
                            <option>Shopify</option>
                            <option>Custom</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="form-row"><label class="form-lbl">Number of Pages</label><input type="number" class="form-inp" placeholder="e.g. 10"></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Required Features</label><textarea class="form-inp" rows="2" placeholder="Login, payment gateway, blog…"></textarea></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Reference Websites</label><input type="text" class="form-inp" placeholder="https://…"></div>
                    <div class="form-row">
                        <label class="form-lbl">Website Payment Status</label>
                        <select class="form-inp">
                            <option>Pending</option>
                            <option>Partial</option>
                            <option>Paid</option>
                        </select>
                    </div>
                </div>

                {{-- Project Timeline --}}
                <div class="proj-section-lbl" style="margin-top:18px;"><i class="bi bi-calendar3"></i> Project Timeline</div>
                <div class="form-grid" style="margin-bottom:0;">
                    <div class="form-row"><label class="form-lbl">Project Start Date</label><input type="date" class="form-inp"></div>
                    <div class="form-row"><label class="form-lbl">Expected Delivery Date</label><input type="date" class="form-inp"></div>
                    <div class="form-row"><label class="form-lbl">Actual Delivery Date</label><input type="date" class="form-inp"></div>
                    <div class="form-row">
                        <label class="form-lbl">Project Status</label>
                        <select class="form-inp">
                            <option>New</option>
                            <option>Design Phase</option>
                            <option>Development</option>
                            <option>Testing</option>
                            <option>Completed</option>
                            <option>On Hold</option>
                        </select>
                    </div>
                </div>

                {{-- Financial --}}
                <div class="proj-section-lbl" style="margin-top:18px;"><i class="bi bi-currency-rupee"></i> Financial Fields</div>
                <div class="form-grid" style="margin-bottom:0;">
                    <div class="form-row"><label class="form-lbl">Project Price *</label><input type="text" class="form-inp" placeholder="₹ Amount"></div>
                    <div class="form-row"><label class="form-lbl">Advance Payment</label><input type="text" class="form-inp" placeholder="₹ Amount"></div>
                    <div class="form-row"><label class="form-lbl">Remaining Amount</label><input type="text" class="form-inp" placeholder="₹ Amount" readonly></div>
                    <div class="form-row">
                        <label class="form-lbl">Payment Status</label>
                        <select class="form-inp">
                            <option>Pending</option>
                            <option>Partial</option>
                            <option>Paid</option>
                        </select>
                    </div>
                    <div class="form-row"><label class="form-lbl">Invoice Number</label><input type="text" class="form-inp" placeholder="INV-XXXX"></div>
                </div>

                {{-- Communication --}}
                <div class="proj-section-lbl" style="margin-top:18px;"><i class="bi bi-chat-dots-fill"></i> Communication & Tracking</div>
                <div class="form-grid" style="margin-bottom:0;">
                    <div class="form-row"><label class="form-lbl">Last Update Date</label><input type="date" class="form-inp"></div>
                    <div class="form-row"><label class="form-lbl">Client Feedback</label><input type="text" class="form-inp" placeholder="Client feedback summary"></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Internal Notes</label><textarea class="form-inp" rows="3" placeholder="Internal notes visible only to team…"></textarea></div>
                </div>

            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('editProjectModal')">Cancel</button>
                <button class="btn-primary-solid" onclick="closeModal('editProjectModal');showToast('success','Project updated!','bi-kanban-fill')">
                    <i class="bi bi-floppy-fill"></i> Update Project
                </button>
            </div>
        </div>
    </div>


    {{-- ── DELETE MODAL ── --}}
    <div class="modal-backdrop" id="deleteProjectModal">
        <div class="modal-box sm-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Delete Project</span>
                <button class="modal-close" onclick="closeModal('deleteProjectModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:var(--t1);">Are you sure?</h3>
                <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">This project will be permanently deleted.<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('deleteProjectModal')">Cancel</button>
                <button style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;"
                    onclick="closeModal('deleteProjectModal');showToast('success','Project deleted!','bi-trash3-fill')">
                    <i class="bi bi-trash3-fill"></i> Delete
                </button>
            </div>
        </div>
    </div>

</main>

<style>
    /* Stat scroll */
    .stat-scroll-row {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: none;
    }

    .stat-scroll-row::-webkit-scrollbar {
        display: none;
    }

    .stat-box {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--bg2);
        border: 1px solid var(--b1);
        border-radius: var(--r);
        padding: 11px 16px;
        min-width: 140px;
        cursor: pointer;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .stat-box::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--sb-color);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .25s ease;
    }

    .stat-box:hover {
        border-color: var(--sb-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, .12);
    }

    .stat-box:hover::after,
    .stat-box.active::after {
        transform: scaleX(1);
    }

    .stat-box.active {
        border-color: var(--sb-color);
        background: var(--bg3);
    }

    .sb-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
        background: color-mix(in srgb, var(--sb-color) 14%, transparent);
        color: var(--sb-color);
    }

    .sb-val {
        font-size: 20px;
        font-weight: 800;
        color: var(--t1);
        letter-spacing: -.4px;
        line-height: 1;
    }

    .sb-lbl {
        font-size: 11px;
        color: var(--t3);
        font-weight: 500;
        margin-top: 2px;
        white-space: nowrap;
    }

    .stat-section-lbl {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--t4);
        padding: 0 6px;
        display: flex;
        align-items: center;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .stat-divider {
        width: 1px;
        height: 40px;
        background: var(--b2);
        flex-shrink: 0;
        margin: 0 4px;
    }

    /* CMS tags */
    .cms-tag {
        font-size: 10.5px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 5px;
    }

    .cms-tag.wordpress {
        background: rgba(33, 117, 155, .12);
        color: #21759b;
    }

    .cms-tag.shopify {
        background: rgba(150, 191, 71, .12);
        color: #96bf47;
    }

    .cms-tag.custom {
        background: rgba(245, 158, 11, .12);
        color: #f59e0b;
    }

    /* Project status pills */
    .proj-status {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 9px;
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

    .proj-status.new {
        background: rgba(245, 158, 11, .12);
        color: #f59e0b;
    }

    .proj-status.new::before {
        background: #f59e0b;
    }

    .proj-status.design {
        background: rgba(6, 182, 212, .12);
        color: #06b6d4;
    }

    .proj-status.design::before {
        background: #06b6d4;
    }

    .proj-status.development {
        background: rgba(99, 102, 241, .12);
        color: #6366f1;
    }

    .proj-status.development::before {
        background: #6366f1;
    }

    .proj-status.testing {
        background: rgba(139, 92, 246, .12);
        color: #8b5cf6;
    }

    .proj-status.testing::before {
        background: #8b5cf6;
    }

    .proj-status.completed {
        background: rgba(16, 185, 129, .12);
        color: #10b981;
    }

    .proj-status.completed::before {
        background: #10b981;
    }

    .proj-status.on-hold {
        background: rgba(100, 116, 139, .12);
        color: #64748b;
    }

    .proj-status.on-hold::before {
        background: #64748b;
    }

    /* Section labels in modal */
    .proj-section-lbl {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--accent);
        background: var(--accent-bg);
        padding: 8px 12px;
        border-radius: var(--r-sm);
        margin-bottom: 12px;
    }

    /* Wide modal */
    .modal-box-lg {
        max-width: 780px !important;
        width: 92vw !important;
    }
</style>

<script>
    /* ── Listen for date range applied from our custom picker ── */
    document.addEventListener('dateRangeApplied', function(e) {
        const { start, end } = e.detail;
        
        function formatDate(date) {
            if(!date) return '';
            let d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
            return [year, month, day].join('-');
        }

        const sInp = document.getElementById('drpStartInput');
        const eInp = document.getElementById('drpEndInput');
        if(sInp && eInp) {
            sInp.value = formatDate(start);
            eInp.value = formatDate(end);
            updateFilters();
        }
    });

    window.updateFilters = function() {
        const form = document.querySelector('.card-actions form') || document.querySelector('.card-actions');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        const url = new URL(window.location.pathname, window.location.origin);
        url.search = params.toString();
        fetchAndReplace(url);
    };

    async function fetchAndReplace(url) {
        const tableWrap = document.getElementById('tableWrap');
        const statsWrap = document.getElementById('statsWrap');
        if (tableWrap) tableWrap.style.opacity = '0.5';

        try {
            const response = await fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            const newTable = doc.getElementById('tableWrap');
            if (newTable && tableWrap) {
                tableWrap.innerHTML = newTable.innerHTML;
            }
            
            const newStats = doc.getElementById('statsWrap');
            if (newStats && statsWrap) {
                statsWrap.innerHTML = newStats.innerHTML;
            }

            if (tableWrap) tableWrap.style.opacity = '1';
            window.history.pushState({}, '', url);
        } catch (error) {
            console.error('AJAX error:', error);
            if (tableWrap) tableWrap.style.opacity = '1';
        }
    }

    let debounceTimer;
    document.addEventListener('input', function(e) {
        if (e.target && e.target.id === 'searchQuery') {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(updateFilters, 500);
        }
    });

    // Intercept pagination clicks
    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('.tf-pagination a');
        if (paginationLink) {
            e.preventDefault();
            fetchAndReplace(new URL(paginationLink.href));
        }
    });

    /* ── Global search shortcuts if needed ── */
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            document.querySelector('.global-search input').focus();
        }
    });
</script>

    <!-- ── Contact Selection Modal (Bootstrap) ── -->
    <div class="modal fade" id="contactSelectionModal" tabindex="-1" aria-labelledby="contactSelectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: var(--bg2); border-color: var(--b2); border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                <div class="modal-header" style="border-bottom-color: var(--b1);">
                    <h5 class="modal-title" id="contactSelectionModalLabel" style="color: #ef4444; font-weight: 700;">Select Option</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: var(--close-filter);"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="list-group list-group-flush" id="contactSelectionOptions">
                    </div>
                </div>
                <div class="modal-footer" style="border-top-color: var(--b1);">
                    <button type="button" class="btn btn-secondary sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function handleContactClick(e, protocol, options) {
            e.preventDefault();
            e.stopPropagation();

            if (!options || options.length === 0) {
                alert('No contact details available');
                return;
            }

            if (options.length === 1) {
                window.location.href = protocol + ':' + options[0];
                return;
            }

            const modalEl = document.getElementById('contactSelectionModal');
            const optionsGroup = document.getElementById('contactSelectionOptions');
            const titleEl = document.getElementById('contactSelectionModalLabel');

            titleEl.textContent = 'Select ' + (protocol === 'tel' ? 'Phone Number' : 'Email Address');
            optionsGroup.innerHTML = '';

            options.forEach(opt => {
                const item = document.createElement('a');
                item.className = 'list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 border-bottom-0';
                item.style.cssText = 'background: transparent; color: var(--t2); border-bottom: 1px solid var(--b1) !important;';
                item.href = protocol + ':' + opt;
                item.innerHTML = `
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(99,102,241,0.1); display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-${protocol === 'tel' ? 'telephone-fill' : 'envelope-fill'}" style="color: var(--accent);"></i>
                    </div>
                    <span style="font-weight: 600; font-size: 15px;">${opt}</span>
                `;
                item.onmouseover = () => { item.style.background = 'var(--bg3)'; item.style.color = 'var(--accent)'; };
                item.onmouseout = () => { item.style.background = 'transparent'; item.style.color = 'var(--t2)'; };
                item.onclick = (e) => {
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                };
                optionsGroup.appendChild(item);
            });

            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    </script>
@endsection