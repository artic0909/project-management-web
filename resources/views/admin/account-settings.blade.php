@extends('admin.layout.app')

@section('title', 'Add Account Settings')

@section('content')


<!-- ═══ PAGE CONTENT AREA ═══ -->
<main class="page-area" id="pageArea">

    <div class="page" id="page-dashboard">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Account Settings</h1>
            </div>
        </div>


        <!-- MAIN GRID -->
        <div class="dash-grid">

            <div class="page span-12" id="page-settings">
                <div class="settings-grid">
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title">Security</div>
                        </div>
                        <div class="card-body">
                            <div class="form-row"><label class="form-lbl">Full Name</label><input type="text" class="form-inp" value="{{ auth()->guard('admin')->user()->name }}"></div>
                            <div class="form-row"><label class="form-lbl">Email</label><input type="email" class="form-inp" value="{{ auth()->guard('admin')->user()->email }}"></div>
                            <div class="form-row"><label class="form-lbl">Current Password</label><input type="password" class="form-inp"></div>
                            <div class="form-row"><label class="form-lbl">New Password</label><input type="password" class="form-inp"></div>
                            <div class="form-row"><label class="form-lbl">Confirm Password</label><input type="password" class="form-inp"></div>
                            <button class="btn-primary-solid">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</main>


@endsection