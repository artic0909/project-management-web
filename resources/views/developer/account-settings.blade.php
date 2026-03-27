@extends('developer.layout.app')

@section('title', 'Account Settings')

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-account-settings">
        <!-- Page Header -->
        <div class="page-header" style="margin-bottom: 30px;">
            <div>
                <h1 class="page-title" style="font-size: 28px; font-weight: 800; letter-spacing: -0.5px;">Account Settings</h1>
                <p class="page-desc" style="color: var(--t3); font-size: 14px;">Manage your profile information and account security</p>
            </div>
        </div>

        @if(session('success'))
            <div class="premium-alert success" style="margin-bottom: 24px;">
                <div class="alert-icon"><i class="bi bi-shield-check"></i></div>
                <div class="alert-content">
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="premium-alert danger" style="margin-bottom: 24px;">
                <div class="alert-icon"><i class="bi bi-exclamation-triangle"></i></div>
                <div class="alert-content">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="dash-grid">
            {{-- Left Column: Profile Info --}}
            <div class="span-8">
                <div class="dash-card premium-settings-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-person-bounding-box" style="color:var(--accent); margin-right:10px;"></i>Profile Information</div>
                    </div>
                    <div class="card-body" style="padding: 30px;">
                        <form action="{{ route('developer.account-settings.update') }}" method="POST">
                            @csrf
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                                <div class="form-group-premium">
                                    <label>Full Name</label>
                                    <input type="text" name="name" class="premium-input" value="{{ old('name', $developer->name) }}" required>
                                </div>
                                <div class="form-group-premium">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="premium-input" value="{{ old('email', $developer->email) }}" required>
                                </div>
                            </div>

                            <div class="form-group-premium" style="margin-bottom: 30px;">
                                <label>Designation</label>
                                <input type="text" name="designation" class="premium-input" value="{{ old('designation', $developer->designation) }}" placeholder="e.g. Senior Full Stack Developer">
                            </div>

                            <button type="submit" class="btn-primary-solid lg">
                                <i class="bi bi-person-check-fill"></i> Update Profile Details
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right Column: Security --}}
            <div class="span-4">
                <div class="dash-card premium-settings-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-shield-lock-fill" style="color:#f59e0b; margin-right:10px;"></i>Account Security</div>
                    </div>
                    <div class="card-body" style="padding: 30px;">
                        <form action="{{ route('developer.account-settings.update') }}" method="POST">
                            @csrf
                            {{-- Hide profile fields but include them to pass validation if updating only password --}}
                            <input type="hidden" name="name" value="{{ $developer->name }}">
                            <input type="hidden" name="email" value="{{ $developer->email }}">
                            
                            <div class="form-group-premium">
                                <label>Current Password</label>
                                <div class="input-with-icon">
                                    <i class="bi bi-key"></i>
                                    <input type="password" name="current_password" class="premium-input" placeholder="••••••••">
                                </div>
                            </div>

                            <div class="form-group-premium" style="margin-top: 20px;">
                                <label>New Password</label>
                                <div class="input-with-icon">
                                    <i class="bi bi-lock"></i>
                                    <input type="password" name="new_password" class="premium-input" placeholder="Min. 8 characters">
                                </div>
                            </div>

                            <div class="form-group-premium" style="margin-top: 20px; margin-bottom: 30px;">
                                <label>Confirm New Password</label>
                                <div class="input-with-icon">
                                    <i class="bi bi-shield-check"></i>
                                    <input type="password" name="new_password_confirmation" class="premium-input" placeholder="Repeat new password">
                                </div>
                            </div>

                            <button type="submit" class="btn-amber-solid lg" style="width: 100%;">
                                <i class="bi bi-arrow-repeat"></i> Update Password
                            </button>
                        </form>
                    </div>
                </div>

                <div class="dash-card" style="margin-top: 24px; background: rgba(var(--accent-rgb), 0.05); border: 1px dashed var(--accent);">
                    <div class="card-body" style="padding: 20px; text-align: center;">
                        <div style="font-size: 13px; color: var(--t3); line-height: 1.5;">
                            <i class="bi bi-info-circle-fill" style="color:var(--accent); display:block; font-size: 20px; margin-bottom: 10px;"></i>
                            Your account is secured with 256-bit encryption. Always use a strong, unique password for your workspace.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    /* ─── PREMIUM ALERT ─── */
    .premium-alert { display: flex; align-items: flex-start; gap: 15px; padding: 16px 20px; border-radius: 12px; border: 1px solid transparent; animation: slideDown 0.4s ease; }
    .premium-alert.success { background: rgba(16, 185, 129, 0.08); border-color: rgba(16, 185, 129, 0.2); }
    .premium-alert.danger { background: rgba(239, 68, 68, 0.08); border-color: rgba(239, 68, 68, 0.2); }
    .alert-icon { font-size: 20px; margin-top: 2px; }
    .success .alert-icon { color: #10b981; }
    .danger .alert-icon { color: #ef4444; }
    .alert-content { color: var(--t2); font-size: 14px; line-height: 1.5; }

    /* ─── SETTINGS CARDS ─── */
    .premium-settings-card { background: var(--bg2); border: 1px solid var(--b1); border-radius: 24px; transition: var(--transition); }
    
    .form-group-premium label { display: block; font-size: 11px; font-weight: 800; color: var(--t4); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
    .premium-input { width: 100%; padding: 12px 16px; background: var(--bg); border: 1px solid var(--b1); border-radius: 10px; color: var(--t1); font-size: 14px; font-weight: 600; transition: var(--transition); }
    .premium-input:focus { border-color: var(--accent); outline: none; box-shadow: 0 0 0 4px rgba(var(--accent-rgb), 0.1); }
    
    .premium-textarea { width: 100%; padding: 12px 16px; background: var(--bg); border: 1px solid var(--b1); border-radius: 10px; color: var(--t1); font-size: 14px; line-height: 1.6; resize: none; transition: var(--transition); }
    .premium-textarea:focus { border-color: var(--accent); outline: none; }

    .input-with-icon { position: relative; }
    .input-with-icon i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--t4); font-size: 16px; pointer-events: none; }
    .input-with-icon .premium-input { padding-left: 42px; }

    /* Buttons */
    .btn-primary-solid.lg { padding: 14px 28px; font-size: 15px; font-weight: 800; border-radius: 12px; display: flex; align-items: center; gap: 10px; cursor: pointer; border: none; background: var(--accent); color: #fff; transition: var(--transition); }
    .btn-primary-solid.lg:hover { filter: brightness(1.1); transform: translateY(-2px); box-shadow: var(--accent-glow); }
    
    .btn-amber-solid.lg { padding: 14px; font-size: 15px; font-weight: 800; border-radius: 12px; display: flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer; border: none; background: #f59e0b; color: #fff; transition: var(--transition); }
    .btn-amber-solid.lg:hover { filter: brightness(1.1); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3); }

    @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

    @media (max-width: 992px) {
        .dash-grid { grid-template-columns: 1fr; }
        .span-8, .span-4 { grid-column: span 12; }
        div[style*="grid-template-columns: 1fr 1fr"] { grid-template-columns: 1fr !important; }
    }
</style>
@endsection