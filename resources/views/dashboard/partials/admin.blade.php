<main class="main-content" id="mainContent">
@include('dashboard.admin.dashboard')
@include('dashboard.admin.analytics')
@include('dashboard.admin.projects')
@include('dashboard.admin.leads')
@include('dashboard.admin.sales')
@include('dashboard.admin.attendance')
@include('dashboard.admin.team')
@include('dashboard.admin.finance')
@include('dashboard.admin.payroll')
@include('dashboard.admin.reports')
@include('dashboard.admin.settings')
</main>

<!-- MODALS -->
@include('dashboard.modals.quickAddModal')
@include('dashboard.modals.addProjectModal')
@include('dashboard.modals.addLeadModal')
@include('dashboard.modals.addOrderModal')
@include('dashboard.modals.addMemberModal')
@include('dashboard.modals.projectDetailModal')
@include('dashboard.modals.leadDetailModal')
@include('dashboard.modals.orderDetailModal')
