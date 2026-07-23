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
                                    <!-- Left Side: Profile Information -->
                                    <div>
                                        <h4 style="margin-bottom: 20px; color: var(--t1); font-size: 16px; font-weight: 700; border-bottom: 1px solid var(--b2); padding-bottom: 10px;">
                                            <i class="bi bi-person-circle" style="margin-right: 8px; color: var(--accent);"></i>Profile Information
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
                                    </div>

                                    <!-- Right Side: Security & Password -->
                                    <div>
                                        <h4 style="margin-bottom: 20px; color: var(--t1); font-size: 16px; font-weight: 700; border-bottom: 1px solid var(--b2); padding-bottom: 10px;">
                                            <i class="bi bi-shield-lock-fill" style="margin-right: 8px; color: #f59e0b;"></i>Security & Password
                                        </h4>


                                        <div class="form-row">
                                            <label class="form-lbl">New Password</label>
                                            <div style="position: relative;">
                                                <input type="password" name="new_password" class="form-inp" placeholder="Enter New Password" value="{{ old('new_password') }}" autocomplete="new-password">
                                                <i class="bi bi-eye-slash toggle-password" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--t3);"></i>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="form-lbl">Confirm Password</label>
                                            <div style="position: relative;">
                                                <input type="password" name="new_password_confirmation" class="form-inp" placeholder="Confirm Password">
                                                <i class="bi bi-eye-slash toggle-password" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--t3);"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bottom: KYC & Additional Details (Full Width) -->
                                @php $user = auth()->guard($routePrefix)->user(); @endphp
                                <div style="margin-top: 40px; padding: 30px; background: var(--bg3); border: 1px dashed var(--b2); border-radius: var(--r);">
                                    <h4 style="margin-bottom: 20px; color: var(--t1); font-size: 18px; font-weight: 700; border-bottom: 1px solid var(--b2); padding-bottom: 15px;">
                                        <i class="bi bi-patch-check-fill" style="margin-right: 8px; color: var(--accent);"></i>KYC & Additional Details
                                    </h4>
                                    
                                    @if(!$user->kyc_submitted)
                                        <div style="margin-bottom: 20px; font-size: 14px; color: var(--t3);">Please provide your KYC details. <b>Note: These can only be submitted once.</b></div>
                                        
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                                            <div>
                                                <div class="form-row">
                                                    <label class="form-lbl">Phone Number <span style="color: red;">*</span></label>
                                                    <input type="text" name="phone" class="form-inp" value="{{ old('phone') }}" required>
                                                </div>
                                                <div class="form-row">
                                                    <label class="form-lbl">Address <span style="color: red;">*</span></label>
                                                    <textarea name="address" class="form-inp" rows="3" required>{{ old('address') }}</textarea>
                                                </div>
                                                <div class="form-row">
                                                    <label class="form-lbl">Aadhar Card Document (Image/PDF) <span style="color: red;">*</span></label>
                                                    <input type="file" name="aadhar_card" class="form-inp" required>
                                                </div>
                                                <div class="form-row">
                                                    <label class="form-lbl">PAN Card Document (Image/PDF)</label>
                                                    <input type="file" name="pan_card" class="form-inp">
                                                </div>
                                            </div>
                                            <div>
                                                <div class="form-row">
                                                    <label class="form-lbl">Voter Card Document (Image/PDF)</label>
                                                    <input type="file" name="voter_card" class="form-inp">
                                                </div>
                                                <div class="form-row">
                                                    <label class="form-lbl">Bank Account Proof (Image/PDF) <span style="color: red;">*</span></label>
                                                    <input type="file" name="bank_account_pic" class="form-inp" required>
                                                </div>
                                                <div class="form-row">
                                                    <label class="form-lbl">Qualification/Experience Details</label>
                                                    <textarea name="qualification_details" class="form-inp" rows="3">{{ old('qualification_details') }}</textarea>
                                                </div>
                                                <div class="form-row">
                                                    <label class="form-lbl">Qualification Attachments (Multiple)</label>
                                                    <input type="file" name="qualification_attachments[]" class="form-inp" multiple accept="image/*,.pdf">
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div style="margin-bottom: 20px; font-size: 14px; color: #16a34a; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                                            <i class="bi bi-check-circle-fill" style="font-size: 20px;"></i> 
                                            KYC Details Submitted Successfully
                                        </div>
                                        <div style="font-size: 14px; color: var(--t2); margin-bottom: 30px; line-height: 1.6; max-width: 800px;">
                                            Your identity and professional credentials have been verified. You can no longer edit these details. If you need to update any information, please contact the administrative department.
                                        </div>

                                        <div style="display: grid; grid-template-columns: 1fr; gap: 24px;">
                                            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
                                                <div style="padding: 20px; background: var(--bg2); border-radius: 12px; border: 1px solid var(--b3);">
                                                    <div style="font-size: 11px; color: var(--t4); text-transform: uppercase; font-weight: 700; margin-bottom: 8px; letter-spacing: 0.5px;">Phone Number</div>
                                                    <div style="font-size: 16px; color: var(--t1); font-weight: 700;">{{ $user->phone ?? 'N/A' }}</div>
                                                </div>
                                                <div style="padding: 20px; background: var(--bg2); border-radius: 12px; border: 1px solid var(--b3);">
                                                    <div style="font-size: 11px; color: var(--t4); text-transform: uppercase; font-weight: 700; margin-bottom: 8px; letter-spacing: 0.5px;">Address</div>
                                                    <div style="font-size: 15px; color: var(--t1); line-height: 1.5; font-weight: 500;">{{ $user->address ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                            
                                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                                                @foreach(['aadhar_card' => 'Aadhar Card', 'pan_card' => 'PAN Card', 'voter_card' => 'Voter Card', 'bank_account_pic' => 'Bank Proof'] as $key => $label)
                                                    <div style="padding: 20px; background: var(--bg2); border-radius: 16px; border: 1px solid var(--b3); display: flex; flex-direction: column;">
                                                        <div style="font-size: 11px; color: var(--t4); text-transform: uppercase; font-weight: 800; margin-bottom: 15px; letter-spacing: 0.5px;">{{ $label }}</div>
                                                        @if($user->$key)
                                                            @php $ext = pathinfo($user->$key, PATHINFO_EXTENSION); @endphp
                                                            @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp', 'gif']))
                                                                <div style="width: 100%; height: 180px; border-radius: 10px; overflow: hidden; border: 1px solid var(--b2); background: var(--bg1);">
                                                                    <img src="{{ asset('storage/' . $user->$key) }}" alt="{{ $label }}" style="width: 100%; height: 100%; object-fit: contain; transition: transform 0.3s ease;">
                                                                </div>
                                                            @else
                                                                <div style="height: 180px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; background: var(--bg1); border-radius: 10px; border: 1px solid var(--b3);">
                                                                    <i class="bi bi-file-earmark-pdf-fill" style="font-size: 48px; color: #ef4444;"></i>
                                                                    <a href="{{ asset('storage/' . $user->$key) }}" target="_blank" style="color: var(--accent); font-size: 13px; font-weight: 700; text-decoration: none;">View PDF Document</a>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <div style="height: 180px; display: flex; align-items: center; justify-content: center; background: var(--bg1); border-radius: 10px; border: 1px dashed var(--b2); color: var(--t4); font-size: 12px;">Not Uploaded</div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                                                <div style="padding: 20px; background: var(--bg2); border-radius: 12px; border: 1px solid var(--b3);">
                                                    <div style="font-size: 11px; color: var(--t4); text-transform: uppercase; font-weight: 700; margin-bottom: 8px;">Qualification Details</div>
                                                    <div style="font-size: 15px; color: var(--t1); line-height: 1.5;">{{ $user->qualification_details ?? 'N/A' }}</div>
                                                </div>

                                                @if($user->qualification_attachments)
                                                    <div style="padding: 20px; background: var(--bg2); border-radius: 12px; border: 1px solid var(--b3);">
                                                        <div style="font-size: 11px; color: var(--t4); text-transform: uppercase; font-weight: 700; margin-bottom: 12px;">Qualification Attachments</div>
                                                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 10px;">
                                                            @foreach($user->qualification_attachments as $attach)
                                                                @php $ext = pathinfo($attach, PATHINFO_EXTENSION); @endphp
                                                                @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp', 'gif']))
                                                                    <div style="aspect-ratio: 1; border-radius: 8px; overflow: hidden; border: 1px solid var(--b2); background: var(--bg1);">
                                                                        <img src="{{ asset('storage/' . $attach) }}" alt="Attachment" style="width: 100%; height: 100%; object-fit: cover;">
                                                                    </div>
                                                                @else
                                                                    <a href="{{ asset('storage/' . $attach) }}" target="_blank" style="aspect-ratio: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 5px; background: var(--bg1); border: 1px solid var(--b2); border-radius: 8px; text-decoration: none; padding: 5px;">
                                                                        <i class="bi bi-file-earmark-fill" style="font-size: 20px; color: var(--t3);"></i>
                                                                        <span style="font-size: 9px; color: var(--t2); font-weight: 700;">{{ strtoupper($ext) }}</span>
                                                                    </a>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
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
                                    <label class="form-lbl">New Password</label>
                                    <div style="position: relative;">
                                        <input type="password" name="new_password" class="form-inp" placeholder="Enter New Password" value="{{ old('new_password') }}" autocomplete="new-password">
                                        <i class="bi bi-eye-slash toggle-password" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--t3);"></i>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Confirm Password</label>
                                    <div style="position: relative;">
                                        <input type="password" name="new_password_confirmation" class="form-inp" placeholder="Confirm Password">
                                        <i class="bi bi-eye-slash toggle-password" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--t3);"></i>
                                    </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-password').forEach(function(icon) {
        icon.addEventListener('click', function() {
            var input = this.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('bi-eye-slash');
                this.classList.add('bi-eye');
            } else {
                input.type = 'password';
                this.classList.remove('bi-eye');
                this.classList.add('bi-eye-slash');
            }
        });
    });
});
</script>
@endsection