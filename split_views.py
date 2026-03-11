import os
import re

file_path = r"d:\Projects\Sweet Developers\project_management\resources\views\test.blade.php"
with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# Split into parts
# Head & CSS
head_end = content.find("</head>") + 7
head_css = content[:head_end]

# Layout wrapper
body_start = content.find("<body>") + 6

# Sidebar
sidebar_start = content.find('<aside class="sidebar" id="sidebar">')
sidebar_end = content.find('</aside>') + 8
sidebar = content[sidebar_start:sidebar_end]

# Topbar
topbar_start = content.find('<header class="topbar" id="topbar">')
topbar_end = content.find('</header>') + 9
topbar = content[topbar_start:topbar_end]

# Admin Dashboard
admin_start = content.find('<div class="page" id="page-dashboard">')
admin_end = content.find('<!-- STUB PAGES -->')
admin_end = content.rfind('</div>', 0, admin_end) # find the closing div of page-dashboard
admin_end = content.rfind('</div>', 0, admin_end) + 6
admin_dashboard = content[admin_start:admin_end]

# Sales Dashboard
sales_start = content.find('<div class="page hidden" id="page-sales-dash">')
sales_end = content.find('<!-- DEV PANEL PAGES -->')
sales_end = content.rfind('</div>', 0, sales_end) + 6
sales_dashboard = content[sales_start:sales_end]

# Dev Dashboard
dev_start = content.find('<div class="page hidden" id="page-dev-dash">')
dev_end = content.find('</main>')
dev_end = content.rfind('</div>', 0, dev_end) + 6
dev_dashboard = content[dev_start:dev_end]

# Modals & JS
modals_js_start = content.find('<!-- ═══════════════════════════════════════════\n     MODALS')
modals_js = content[modals_js_start:]

# Write Layout
layout_content = f"""{head_css}
<body>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    @include('components.sidebar')
    <div class="main-wrap" id="mainWrap">
        @include('components.topbar')
        <main class="page-area" id="pageArea">
            @yield('content')
        </main>
    </div>
    {modals_js}
"""

components_dir = r"d:\Projects\Sweet Developers\project_management\resources\views\components"
layouts_dir = r"d:\Projects\Sweet Developers\project_management\resources\views\layouts"
dashboard_dir = r"d:\Projects\Sweet Developers\project_management\resources\views\dashboard"
partials_dir = r"d:\Projects\Sweet Developers\project_management\resources\views\dashboard\partials"

os.makedirs(components_dir, exist_ok=True)
os.makedirs(layouts_dir, exist_ok=True)
os.makedirs(dashboard_dir, exist_ok=True)
os.makedirs(partials_dir, exist_ok=True)

with open(os.path.join(layouts_dir, "app.blade.php"), "w", encoding="utf-8") as f:
    f.write(layout_content)

with open(os.path.join(components_dir, "sidebar.blade.php"), "w", encoding="utf-8") as f:
    f.write(sidebar)

with open(os.path.join(components_dir, "topbar.blade.php"), "w", encoding="utf-8") as f:
    f.write(topbar)

with open(os.path.join(partials_dir, "admin.blade.php"), "w", encoding="utf-8") as f:
    f.write(admin_dashboard.replace('class="page"', 'class="page"').replace('id="page-dashboard"', 'id="page-dashboard" style="display:block;"'))

with open(os.path.join(partials_dir, "sale.blade.php"), "w", encoding="utf-8") as f:
    f.write(sales_dashboard.replace('class="page hidden"', 'class="page"').replace('id="page-sales-dash"', 'id="page-sales-dash" style="display:block;"'))

with open(os.path.join(partials_dir, "developer.blade.php"), "w", encoding="utf-8") as f:
    f.write(dev_dashboard.replace('class="page hidden"', 'class="page"').replace('id="page-dev-dash"', 'id="page-dev-dash" style="display:block;"'))

# Main dashboard file
dashboard_main = """@extends('layouts.app')

@section('content')
    @if(Auth::guard('admin')->check())
        @include('dashboard.partials.admin')
    @elseif(Auth::guard('sale')->check())
        @include('dashboard.partials.sale')
    @elseif(Auth::guard('developer')->check())
        @include('dashboard.partials.developer')
    @else
        <!-- Fallback or normal user view -->
        <div class="page">
            <div class="page-header">
                <h1 class="page-title">Welcome</h1>
                <p class="page-desc">You are logged in.</p>
            </div>
        </div>
    @endif
@endsection
"""
with open(os.path.join(dashboard_dir, "dashboard.blade.php"), "w", encoding="utf-8") as f:
    f.write(dashboard_main)
