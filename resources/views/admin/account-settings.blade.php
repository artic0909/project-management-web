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
                            <form action="{{ route('admin.account-settings.update') }}" method="POST">
                                @csrf
                                <div class="form-row">
                                    <label class="form-lbl">Full Name</label>
                                    <input type="text" name="name" class="form-inp" value="{{ old('name', auth()->guard('admin')->user()->name) }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Email</label>
                                    <input type="email" name="email" class="form-inp" value="{{ old('email', auth()->guard('admin')->user()->email) }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Current Password</label>
                                    <input type="password" name="current_password" class="form-inp" placeholder="Enter Current Password" value="{{ old('current_password') }}" autocomplete="new-password">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">New Password</label>
                                    <input type="password" name="new_password" class="form-inp" placeholder="Enter New Password" value="{{ old('new_password') }}" autocomplete="new-password">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Confirm Password</label>
                                    <input type="password" name="new_password_confirmation" class="form-inp" placeholder="Confirm Password">
                                </div>
                                <button type="submit" class="btn-primary-solid">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</main>


@endsection