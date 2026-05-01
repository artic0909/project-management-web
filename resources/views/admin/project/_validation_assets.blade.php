{{--
    admin/project/_validation_assets.blade.php
    jQuery validation for Project Create and Edit forms.
--}}

<script>
$(document).ready(function() {
    const form = $('#projectCreateForm');
    
    if (form.length === 0) {
        form = $('form[action*="projects"]');
    }

    const submitBtn = form.find('button[type="submit"]');

    form.on('submit', function(e) {
        let isValid = true;
        let firstErrorEl = null;

        // Clear previous errors
        $('.field-error').remove();
        $('.is-invalid').removeClass('is-invalid');
        $('.ms-trigger, .os-trigger').css('border-color', '');

        function markError(el, msg) {
            isValid = false;
            
            let target = $(el);
            let scrollTarget = target;

            if (target.attr('name') === 'order_id') {
                const trigger = target.closest('.order-select-wrap').find('.os-trigger');
                trigger.addClass('is-invalid');
                scrollTarget = trigger;
            } else if (target.hasClass('phone-num-inp')) {
                target.closest('.phone-wrap').addClass('is-invalid');
                scrollTarget = target.closest('.phone-wrap');
            } else if (target.closest('.ms-wrap').length > 0) {
                target.closest('.ms-wrap').find('.ms-trigger').addClass('is-invalid');
                scrollTarget = target.closest('.ms-wrap').find('.ms-trigger');
            } else {
                target.addClass('is-invalid');
            }

            if (!firstErrorEl) firstErrorEl = scrollTarget;
            
            let errorSpan = $('<span class="field-error"></span>').text(msg);
            
            if (target.attr('name') === 'order_id') {
                errorSpan.appendTo(target.closest('.order-select-wrap'));
            } else if (target.closest('.ms-wrap').length > 0) {
                errorSpan.appendTo(target.closest('.ms-wrap'));
            } else if (target.hasClass('phone-num-inp')) {
                errorSpan.insertAfter(target.closest('.phone-wrap'));
            } else {
                errorSpan.insertAfter(target);
            }

            $(el).one('input change', function() {
                if (target.attr('name') === 'order_id') {
                    target.closest('.order-select-wrap').find('.os-trigger').removeClass('is-invalid');
                } else if (target.hasClass('phone-num-inp')) {
                    target.closest('.phone-wrap').removeClass('is-invalid');
                } else if (target.closest('.ms-wrap').length > 0) {
                    target.closest('.ms-wrap').find('.ms-trigger').removeClass('is-invalid');
                } else {
                    target.removeClass('is-invalid');
                }
                errorSpan.fadeOut(200, function() { $(this).remove(); });
            });
        }

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
                    markError(el, `${f.label} is required.`);
                }
            }
        });

        // 2. Email Validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        $('input[name="email[]"]').each(function() {
            const val = $(this).val().trim();
            if (val !== '' && !emailRegex.test(val)) {
                markError(this, 'Please enter a valid email address.');
            }
        });

        // 3. Phone Validation
        $('input[name="phone[]"]').each(function() {
            const val = $(this).val().trim();
            if (val !== '' && val.length < 7) {
                markError(this, 'Phone number is too short.');
            }
        });

        // 4. Multi-select Validation
        if ($('input[name="plan_ids[]"]:checked').length === 0) {
            markError($('input[name="plan_ids[]"]').first(), 'Plan Name is required.');
        }

        // 5. Special check for "Others" CMS
        const cmsSelect = $('#cmsSelect');
        if (cmsSelect.val() === 'Others') {
            const customCms = $('#cmsCustomInput');
            if (!customCms.val() || customCms.val().trim() === '') {
                markError(customCms, 'Please specify the platform.');
            }
        }

        // 6. Zip Code
        const zipField = $('input[name="zip_code"]');
        if (zipField.val() && zipField.val().length !== 6) {
            markError(zipField, 'Zip Code must be exactly 6 digits.');
        }

        if (!isValid) {
            e.preventDefault();
            e.stopPropagation();
            
            if (firstErrorEl) {
                $('html, body').animate({
                    scrollTop: $(firstErrorEl).offset().top - 120
                }, 500);
            }
            return false;
        }

        // Show loading state
        submitBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Creating Project...');
        return true;
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
        animation: fadeInError 0.2s ease;
    }
    @keyframes fadeInError {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
