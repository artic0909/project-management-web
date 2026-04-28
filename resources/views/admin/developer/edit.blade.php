@extends('admin.layout.app')

@section('title', 'Update Developer')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-dashboard">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <a href="{{ route($routePrefix . '.developer') }}" class="btn-ghost" style="margin-bottom: 10px; display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; color: var(--t2);">
                    <i class="bi bi-arrow-left"></i> Back to Developers
                </a>
                <h1 class="page-title">Update Developer: {{ $developer->name }}</h1>
            </div>
            @if($developer->profile_image)
                <div style="flex-shrink: 0;">
                    <img src="{{ asset('storage/' . $developer->profile_image) }}" alt="Profile Image" style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover; border: 3px solid #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                </div>
            @endif
        </div>

        @if (session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0; display: flex; align-items: center; gap: 8px;">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fecaca;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="dash-grid">
            <div class="dash-card span-12">
                <div class="card-head">
                    <div class="card-title">Developer Details</div>
                </div>
                <div class="card-body">
                    <form action="{{ route($routePrefix . '.developer.update', $developer->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-grid">
                            <div class="form-row">
                                <label class="form-lbl">Developer *</label>
                                <input type="text" name="name" class="form-inp" value="{{ old('name', $developer->name) }}" required>
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Email *</label>
                                <input type="email" name="email" class="form-inp" value="{{ old('email', $developer->email) }}" required>
                            </div>
                            <div class="form-row" style="grid-column: 1 / -1;">
                                <label class="form-lbl">Designation *</label>
                                <input type="text" name="designation" class="form-inp" value="{{ old('designation', $developer->designation) }}" required>
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Set Password (leave blank to keep current)</label>
                                <input type="password" name="password" class="form-inp" autocomplete="new-password">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-inp" autocomplete="new-password">
                            </div>

                            <div class="form-row" style="grid-column: 1 / -1;">
                                <hr style="border: 0; border-top: 1px solid var(--b2); margin: 20px 0 10px;">
                                <h4 style="font-size: 16px; font-weight: 600; color: var(--t1); margin-bottom: 8px;">KYC & Additional Details</h4>
                            </div>

                            <div class="form-row">
                                <label class="form-lbl">Phone Number</label>
                                <input type="text" name="phone" class="form-inp" value="{{ old('phone', $developer->phone) }}" placeholder="Phone Number">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Address</label>
                                <textarea name="address" class="form-inp" rows="2" placeholder="Address">{{ old('address', $developer->address) }}</textarea>
                            </div>

                            <div class="form-row">
                                <label class="form-lbl">Profile Image</label>
                                <input type="file" name="profile_image" class="form-inp" accept="image/*">
                            </div>

                            <div class="form-row">
                                <label class="form-lbl">Aadhar Card Document</label>
                                @if($developer->aadhar_card)
                                    <div style="margin-bottom: 8px;">
                                        <a href="{{ asset('storage/' . $developer->aadhar_card) }}" target="_blank" style="color: var(--accent); font-size: 13px;"><i class="bi bi-file-earmark-text"></i> View Current File</a>
                                    </div>
                                @endif
                                <input type="file" name="aadhar_card" class="form-inp">
                            </div>

                            <div class="form-row">
                                <label class="form-lbl">PAN Card Document</label>
                                @if($developer->pan_card)
                                    <div style="margin-bottom: 8px;">
                                        <a href="{{ asset('storage/' . $developer->pan_card) }}" target="_blank" style="color: var(--accent); font-size: 13px;"><i class="bi bi-file-earmark-text"></i> View Current File</a>
                                    </div>
                                @endif
                                <input type="file" name="pan_card" class="form-inp">
                            </div>

                            <div class="form-row">
                                <label class="form-lbl">Voter Card Document</label>
                                @if($developer->voter_card)
                                    <div style="margin-bottom: 8px;">
                                        <a href="{{ asset('storage/' . $developer->voter_card) }}" target="_blank" style="color: var(--accent); font-size: 13px;"><i class="bi bi-file-earmark-text"></i> View Current File</a>
                                    </div>
                                @endif
                                <input type="file" name="voter_card" class="form-inp">
                            </div>

                            <div class="form-row">
                                <label class="form-lbl">Bank Account Proof</label>
                                @if($developer->bank_account_pic)
                                    <div style="margin-bottom: 8px;">
                                        <a href="{{ asset('storage/' . $developer->bank_account_pic) }}" target="_blank" style="color: var(--accent); font-size: 13px;"><i class="bi bi-file-earmark-text"></i> View Current File</a>
                                    </div>
                                @endif
                                <input type="file" name="bank_account_pic" class="form-inp">
                            </div>

                            <div class="form-row">
                                <label class="form-lbl">Qualification/Experience Details</label>
                                <textarea name="qualification_details" class="form-inp" rows="2">{{ old('qualification_details', $developer->qualification_details) }}</textarea>
                            </div>

                            <div class="form-row">
                                <label class="form-lbl">Qualification Attachments (Multiple)</label>
                                @if($developer->qualification_attachments && count($developer->qualification_attachments) > 0)
                                    <div style="margin-bottom: 8px; display: flex; gap: 8px; flex-wrap: wrap;">
                                        @foreach($developer->qualification_attachments as $index => $attachment)
                                            <a href="{{ asset('storage/' . $attachment) }}" target="_blank" style="color: var(--accent); font-size: 13px; background: var(--bg3); padding: 4px 8px; border-radius: 4px; border: 1px solid var(--b2);"><i class="bi bi-paperclip"></i> File {{ $index + 1 }}</a>
                                        @endforeach
                                    </div>
                                @endif
                                <input type="file" name="qualification_attachments[]" class="form-inp" multiple accept="image/*,.pdf">
                            </div>
                        </div>

                        <div style="margin-top: 30px; display: flex; gap: 12px; padding-top: 20px; border-top: 1px solid var(--b2);">
                            <button type="submit" class="btn-primary-solid">
                                <i class="bi bi-pencil-fill"></i> Save Changes
                            </button>
                            <a href="{{ route($routePrefix . '.developer') }}" class="btn-ghost">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
