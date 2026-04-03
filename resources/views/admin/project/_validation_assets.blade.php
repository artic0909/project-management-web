{{--
    admin/project/_validation_assets.blade.php
    jQuery validation for Project Create and Edit forms.
--}}

<script>
$(document).ready(function() {
    const form = $('form[action*="projects"]');
    form.attr('novalidate', 'novalidate');

    form.on('submit', function(e) {
        let isValid = true;
        let firstErrorEl = null;

        function markError(el, msg) {
            isValid = false;
            if (!firstErrorEl) firstErrorEl = el;
            $(el).addClass('is-invalid');
            
            let wrap = $(el).closest('.form-row');
            let errorSpan = wrap.find('.field-error');
            
            if (errorSpan.length === 0) {
                errorSpan = $('<span class="field-error"></span>').appendTo(wrap);
            }
            errorSpan.text(msg).show();

            $(el).one('input change', function() {
                $(el).removeClass('is-invalid');
                errorSpan.fadeOut();
            });
        }

        // Clear previous
        $('.ms-trigger').css('border-color', '');
        $('.field-error').hide();
        $('.is-invalid').removeClass('is-invalid');

        // 1. Basic Required Fields
        const requiredFields = [
            { name: 'order_id', label: 'Order Selection' },
            { name: 'first_name', label: 'First Name' },
            { name: 'last_name', label: 'Last Name' },
            { name: 'company_name', label: 'Company Name' },
            { name: 'state', label: 'State' },
            { name: 'city', label: 'City' },
            { name: 'full_address', label: 'Full Address' },
            { name: 'zip_code', label: 'Zip Code' },
            { name: 'domain_name', label: 'Domain Name' },
            { name: 'username', label: 'Website Username' },
            { name: 'password', label: 'Website Password' },
            { name: 'cms_platform', label: 'CMS / Platform' },
            { name: 'domain_provider_name', label: 'Domain Provider Name' },
            { name: 'domain_renewal_price', label: 'Domain Renewal Price' },
            { name: 'hosting_provider_name', label: 'Hosting Provider Name' },
            { name: 'hosting_renewal_price', label: 'Hosting Renewal Price' },
            { name: 'primary_domain_name', label: 'Primary Domain Name' },
            { name: 'project_status_id', label: 'Project Status' },
            { name: 'order_date_create', label: 'Order Creation Date' }
        ];

        requiredFields.forEach(f => {
            const el = $(`[name="${f.name}"]`);
            if (el.length > 0) {
                if (!el.val() || el.val().trim() === '') {
                    markError(el[0], `${f.label} is required.`);
                }
            }
        });

        // 2. Email Validation
        const emails = $('input[name="email[]"]');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let hasValidEmail = false;
        emails.each(function() {
            const val = $(this).val().trim();
            if (val !== '') {
                if (!emailRegex.test(val)) {
                    markError(this, 'Please enter a valid email address.');
                } else {
                    hasValidEmail = true;
                }
            }
        });
        if (!hasValidEmail && emails.length > 0) {
            markError(emails[0], 'At least one valid email address is required.');
        }

        // 3. Phone Validation
        const phones = $('input[name="phone[]"]');
        let hasValidPhone = false;
        phones.each(function() {
            const val = $(this).val().trim();
            if (val !== '') {
                if (!/^\d+$/.test(val)) {
                    markError(this, 'Phone number must contain only digits.');
                } else if (val.length < 8) {
                    markError(this, 'Phone number is too short.');
                } else {
                    hasValidPhone = true;
                }
            }
        });
        if (!hasValidPhone && phones.length > 0) {
            markError(phones[0], 'At least one phone number is required.');
        }

        // 4. Multi-select Validation (Plans)
        const msFields = [
            { id: 'planWrap', name: 'plan_ids[]', label: 'Plan Name' }
        ];
        msFields.forEach(ms => {
            const checked = $(`input[name="${ms.name}"]:checked`);
            if (checked.length === 0) {
                const wrap = $(`#${ms.id}`);
                const trigger = wrap.find('.ms-trigger');
                trigger.css('border-color', '#ef4444');
                
                let err = wrap.parent().find('.field-error');
                if (err.length === 0) {
                    err = $('<span class="field-error"></span>').appendTo(wrap.parent());
                }
                err.text(`${ms.label} is required.`).show();
                
                if (!firstErrorEl) firstErrorEl = trigger[0];
                isValid = false;

                $(`input[name="${ms.name}"]`).one('change', function() {
                    trigger.css('border-color', '');
                    err.fadeOut();
                });
            }
        });

        // 5. Special check for "Others" CMS
        const cmsSelect = $('#cmsSelect');
        if (cmsSelect.val() === 'Others') {
            const customCms = $('#cmsCustomInput');
            if (!customCms.val() || customCms.val().trim() === '') {
                markError(customCms[0], 'Please specify the platform.');
            }
        }

        if (!isValid) {
            e.preventDefault();
            if (firstErrorEl) {
                $('html, body').animate({
                    scrollTop: $(firstErrorEl).offset().top - 150
                }, 500);
            }
            return false;
        }
    });
});
</script>

<style>
    .is-invalid {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.1) !important;
    }
    .field-error {
        color: #ef4444;
        font-size: 11px;
        font-weight: 600;
        margin-top: 4px;
        display: block;
    }
</style>
