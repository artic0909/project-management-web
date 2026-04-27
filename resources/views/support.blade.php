<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Center — Orion Technologies</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Clash+Display:wght@400;500;600;700&family=Instrument+Sans:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --ink: #07080c;
            --paper: #fcfcfa;
            --paper2: #f5f4ef;
            --accent: #ff4d1c;
            --t1: #07080c;
            --t2: #3a3d4a;
            --t3: #6b6f82;
            --border: rgba(7, 8, 12, .08);
            --r-lg: 24px;
            --fd: 'Clash Display', sans-serif;
            --fb: 'Instrument Sans', sans-serif;
        }

        body {
            font-family: var(--fb);
            background-color: var(--paper);
            background-image: 
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 40px 40px;
            color: var(--t1);
            margin: 0;
            line-height: 1.6;
            min-height: 100vh;
        }

        .support-container {
            max-width: 900px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .support-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .support-header h1 {
            font-family: var(--fd);
            font-size: clamp(32px, 8vw, 48px);
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 12px;
        }

        .support-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--r-lg);
            padding: clamp(24px, 5vw, 48px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
        }

        .form-label {
            font-weight: 600;
            font-size: 14px;
            color: var(--t2);
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.2s;
            background-color: var(--paper2);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(255, 77, 28, 0.1);
            background-color: #fff;
        }

        .submit-btn {
            background: var(--ink);
            color: #fff;
            padding: 14px 40px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.2s;
            width: auto;
        }

        .submit-btn:hover {
            background: var(--accent);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 77, 28, 0.2);
        }

        /* Error Styling matching Bootstrap */
        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
            display: block;
            font-weight: 500;
        }
        
        input.error, select.error, textarea.error {
            border-color: #dc3545 !important;
        }

        label.error {
            color: #dc3545;
            font-size: 12px;
            font-weight: 600;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .support-container { margin: 40px auto; }
            .support-card { padding: 24px; }
            .submit-btn { width: 100%; }
        }

        /* ── CUSTOM FILE INPUT ── */
        .file-upload-wrapper {
            position: relative;
            background: var(--paper2);
            border: 2px dashed var(--border);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
        }
        .file-upload-wrapper:hover {
            border-color: var(--accent);
            background: rgba(255, 77, 28, 0.02);
        }
        .file-upload-wrapper input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }
        .file-upload-content i {
            font-size: 24px;
            color: var(--t3);
            margin-bottom: 8px;
            display: block;
        }
        .file-upload-content span {
            font-size: 14px;
            font-weight: 500;
            color: var(--t2);
        }
        .file-name-display {
            margin-top: 8px;
            font-size: 13px;
            color: var(--accent);
            font-weight: 600;
            display: none;
        }
    </style>
    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <style>
        .ck-editor__editable {
            min-height: 200px;
            background-color: var(--paper2) !important;
            border-radius: 0 0 12px 12px !important;
        }
        .ck-toolbar {
            border-radius: 12px 12px 0 0 !important;
            background-color: #fff !important;
            border: 1px solid var(--border) !important;
        }
        .ck-content {
            border: 1px solid var(--border) !important;
            transition: all 0.2s;
        }
        .ck-focused {
            border-color: var(--accent) !important;
            box-shadow: 0 0 0 4px rgba(255, 77, 28, 0.1) !important;
        }
    </style>
</head>
<body>
    <div class="support-container">
        <div class="support-header">
            <span class="badge rounded-pill mb-2" style="background: rgba(255, 77, 28, 0.1); color: var(--accent); padding: 8px 16px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em;">Help Center</span>
            <h1>Support & Help</h1>
            <p class="text-muted">Need technical assistance? Submit a ticket below.</p>
        </div>

        @if(session('success'))
            <style>
                .tkt-modal-overlay {
                    position: fixed; inset: 0; background: rgba(7, 8, 12, 0.75); backdrop-filter: blur(8px);
                    z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 20px;
                }
                .tkt-modal-box {
                    background: #fff; border-radius: 24px; width: 100%; max-width: 440px;
                    padding: 40px; text-align: center; box-shadow: 0 30px 60px rgba(0,0,0,0.12);
                    animation: scaleUp 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); position: relative;
                }
                @keyframes scaleUp { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
                .tkt-close {
                    position: absolute; top: 20px; right: 20px; width: 32px; height: 32px;
                    background: var(--paper2); border-radius: 50%; display: flex; align-items: center; justify-content: center;
                    cursor: pointer; color: var(--t3); transition: all 0.2s; border: none;
                }
                .tkt-close:hover { background: #fee2e2; color: #dc3545; }
                .tkt-icon {
                    width: 80px; height: 80px; background: rgba(0, 195, 127, 0.1); color: #00c37f;
                    border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 24px;
                }
                .tkt-number {
                    background: rgba(26, 86, 255, 0.08); border: 1.5px dashed rgba(26, 86, 255, 0.3);
                    color: var(--blue); font-size: 24px; font-weight: 800; padding: 12px 24px; border-radius: 12px;
                    display: inline-block; margin: 20px 0; letter-spacing: 1px; font-family: var(--fd);
                }
            </style>
            <div class="tkt-modal-overlay" id="tktSuccessModal">
                <div class="tkt-modal-box">
                    <button class="tkt-close" onclick="document.getElementById('tktSuccessModal').style.display='none'"><i class="bi bi-x-lg"></i></button>
                    <div class="tkt-icon"><i class="bi bi-check2"></i></div>
                    <h3 style="font-family: var(--fd); font-weight: 700; color: var(--t1);">Ticket Submitted!</h3>
                    <p style="color: var(--t3); font-size: 15px; margin-top: 8px;">{{ session('success') }}</p>
                    <div class="tkt-number">{{ session('ticket_no') }}</div>
                    <p style="color: var(--t4); font-size: 13px;">Please save this ticket number for future reference.</p>
                    <button class="submit-btn" style="width: 100%; margin-top: 10px;" onclick="document.getElementById('tktSuccessModal').style.display='none'">Got it, Thanks!</button>
                </div>
            </div>
        @endif

        <div class="support-card">
            <form action="{{ route('support.store') }}" method="POST" enctype="multipart/form-data" id="supportForm" novalidate>
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" placeholder="Your organization" value="{{ old('company_name') }}">
                        @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="your_name" class="form-control @error('your_name') is-invalid @enderror" placeholder="John Doe" required value="{{ old('your_name') }}">
                        @error('your_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="name@company.com" required value="{{ old('email') }}">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="+1 (555) 000-0000" required value="{{ old('phone') }}">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Domain Name</label>
                        <input type="text" name="domain_name" class="form-control" placeholder="example.com" value="{{ old('domain_name') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Priority <span class="text-danger">*</span></label>
                        <select name="priority" class="form-select" required>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : 'default selected' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" placeholder="Billing Issue" required value="{{ old('subject') }}">
                        @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Detail Message <span class="text-danger">*</span></label>
                        <div style="background:#fff; border-radius:12px; overflow:hidden;">
                            <textarea name="message" id="editor" class="form-control @error('message') is-invalid @enderror" rows="8" placeholder="How can we help you?">{{ old('message') }}</textarea>
                        </div>
                        @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Attachment (Optional)</label>
                        <div class="file-upload-wrapper" id="fileWrapper">
                            <div class="file-upload-content">
                                <i class="bi bi-cloud-arrow-up"></i>
                                <span>Click or Drag & Drop proof/screenshot</span>
                                <p class="text-muted small mb-0 mt-1">Accepts images (JPG, PNG, GIF) up to 2MB</p>
                            </div>
                            <input type="file" name="attachment" id="fileInput" accept="image/*" onchange="handleFileSelect(this)">
                            <div class="file-name-display" id="fileName">No file selected</div>
                        </div>
                        @error('attachment') <div class="error-message" style="margin-top: 8px;">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 pt-2">
                        <button type="submit" class="submit-btn" style="height: 54px; min-width: 240px; display: flex; align-items: center; justify-content: center; gap: 10px;">
                            <span>Submit Support Ticket</span>
                            <i class="bi bi-send-fill" style="opacity: 0.8;"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize CKEditor
            ClassicEditor
                .create(document.querySelector('#editor'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'],
                })
                .then(editor => {
                    // Update validation on change
                    editor.model.document.on('change:data', () => {
                        $('#editor').val(editor.getData());
                    });
                })
                .catch(error => {
                    console.error(error);
                });

            window.handleFileSelect = function(input) {
                const fileName = document.getElementById('fileName');
                const wrapper = document.getElementById('fileWrapper');
                if (input.files && input.files[0]) {
                    fileName.innerText = "Selected: " + input.files[0].name;
                    fileName.style.display = "block";
                    wrapper.style.borderColor = "var(--accent)";
                    wrapper.style.background = "rgba(255, 77, 28, 0.04)";
                } else {
                    fileName.style.display = "none";
                    wrapper.style.borderColor = "var(--border)";
                    wrapper.style.background = "var(--paper2)";
                }
            };

            $("#supportForm").validate({
                ignore: [], // Don't ignore hidden fields (CKEditor replaces original textarea)
                rules: {
                    your_name: "required",
                    email: { required: true, email: true },
                    phone: "required",
                    subject: "required",
                    message: {
                        required: function() {
                            // Custom check for CKEditor content
                            return document.querySelector('#editor').value.trim() === '';
                        },
                        minlength: 10
                    }
                },
                messages: {
                    your_name: "Please enter your name",
                    email: "Enter a valid email address",
                    phone: "Phone number is required",
                    subject: "Subject cannot be empty",
                    message: {
                        required: "Please describe your request",
                        minlength: "Message must be at least 10 characters"
                    }
                },
                errorElement: "div",
                errorClass: "error-message",
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                errorPlacement: function(error, element) {
                    if (element.attr("name") == "message") {
                        error.insertAfter(".ck-editor");
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
        });
    </script>
</body>
</html>
