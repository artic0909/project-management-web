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
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title">Security</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route($routePrefix . '.account-settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            @if(in_array($routePrefix, ['sale', 'developer']))
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start;">
                                    <!-- Left Side: Profile & KYC -->
                                    <div>
                                        <h4 style="margin-bottom: 20px; color: var(--t1); font-size: 16px; font-weight: 700; border-bottom: 1px solid var(--b2); padding-bottom: 10px;">
                                            <i class="bi bi-person-circle" style="margin-right: 8px; color: var(--accent);"></i>Profile Information & KYC
                                        </h4>

                                        <div class="form-row">
                                            <label class="form-lbl">Full Name</label>
                                            <input type="text" name="name" class="form-inp" value="{{ old('name', auth()->guard($routePrefix)->user()->name) }}">
                                        </div>
                                        <div class="form-row">
                                            <label class="form-lbl">Email</label>
                                            <input type="email" name="email" class="form-inp" value="{{ old('email', auth()->guard($routePrefix)->user()->email) }}">
                                        </div>
                                        <div class="form-row">
                                            <label class="form-lbl">Profile Image</label>
                                            @if(auth()->guard($routePrefix)->user()->profile_image)
                                                <div style="margin-bottom: 10px;">
                                                    <img src="{{ asset('storage/' . auth()->guard($routePrefix)->user()->profile_image) }}" alt="Profile" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid var(--b2);">
                                                </div>
                                            @endif
                                            <input type="file" name="profile_image" class="form-inp" accept="image/*">
                                        </div>

                                        @php $user = auth()->guard($routePrefix)->user(); @endphp
                                        <div style="margin-top: 30px; padding: 20px; background: var(--bg3); border: 1px dashed var(--b2); border-radius: var(--r);">
                                            <h4 style="margin-bottom: 16px; color: var(--t1); font-size: 15px; font-weight: 700;">KYC & Additional Details</h4>
                                            
                                            @if(!$user->kyc_submitted)
                                                <div style="margin-bottom: 12px; font-size: 13px; color: var(--t3);">Please provide your KYC details. <b>Note: These can only be submitted once.</b></div>
                                                
                                                <div class="form-row">
                                                    <label class="form-lbl">Phone Number</label>
                                                    <input type="text" name="phone" class="form-inp" value="{{ old('phone') }}">
                                                </div>
                                                <div class="form-row">
                                                    <label class="form-lbl">Address</label>
                                                    <textarea name="address" class="form-inp" rows="3">{{ old('address') }}</textarea>
                                                </div>
                                                <div class="form-row">
                                                    <label class="form-lbl">Aadhar Card Document (Image/PDF)</label>
                                                    <input type="file" name="aadhar_card" class="form-inp">
                                                </div>
                                                <div class="form-row">
                                                    <label class="form-lbl">PAN Card Document (Image/PDF)</label>
                                                    <input type="file" name="pan_card" class="form-inp">
                                                </div>
                                                <div class="form-row">
                                                    <label class="form-lbl">Voter Card Document (Image/PDF)</label>
                                                    <input type="file" name="voter_card" class="form-inp">
                                                </div>
                                                <div class="form-row">
                                                    <label class="form-lbl">Bank Account Proof (Image/PDF)</label>
                                                    <input type="file" name="bank_account_pic" class="form-inp">
                                                </div>
                                                <div class="form-row">
                                                    <label class="form-lbl">Qualification/Experience Details</label>
                                                    <textarea name="qualification_details" class="form-inp" rows="3">{{ old('qualification_details') }}</textarea>
                                                </div>
                                                <div class="form-row">
                                                    <label class="form-lbl">Qualification Attachments (Multiple)</label>
                                                    <input type="file" name="qualification_attachments[]" class="form-inp" multiple accept="image/*,.pdf">
                                                </div>
                                            @else
                                                <div style="margin-bottom: 12px; font-size: 13px; color: #16a34a; font-weight: 600;"><i class="bi bi-check-circle-fill"></i> KYC Details Submitted</div>
                                                <div style="font-size: 13px; color: var(--t2);">Your KYC details have been successfully submitted and are under review or approved. You can no longer edit these details. Contact administration for changes.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Right Side: Security & Password -->
                                    <div>
                                        <h4 style="margin-bottom: 20px; color: var(--t1); font-size: 16px; font-weight: 700; border-bottom: 1px solid var(--b2); padding-bottom: 10px;">
                                            <i class="bi bi-shield-lock-fill" style="margin-right: 8px; color: #f59e0b;"></i>Security & Password
                                        </h4>

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
                                    </div>
                                </div>
                            @else
                                <!-- Original layout for Admin -->
                                <div class="form-row">
                                    <label class="form-lbl">Full Name</label>
                                    <input type="text" name="name" class="form-inp" value="{{ old('name', auth()->guard($routePrefix)->user()->name) }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Email</label>
                                    <input type="email" name="email" class="form-inp" value="{{ old('email', auth()->guard($routePrefix)->user()->email) }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Profile Image</label>
                                    @if(auth()->guard($routePrefix)->user()->profile_image)
                                        <div style="margin-bottom: 10px;">
                                            <img src="{{ asset('storage/' . auth()->guard($routePrefix)->user()->profile_image) }}" alt="Profile" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid var(--b2);">
                                        </div>
                                    @endif
                                    <input type="file" name="profile_image" class="form-inp" accept="image/*">
                                </div>

                                <h4 style="margin: 24px 0 16px; color: var(--t1); font-size: 15px; font-weight: 700;">Password Change (Optional)</h4>
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
                            @endif

                            <div style="display: flex; justify-content: center; margin-top: 40px; border-top: 1px solid var(--b2); padding-top: 25px;">
                                <button type="submit" class="btn-primary-solid">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

</main>


@endsection