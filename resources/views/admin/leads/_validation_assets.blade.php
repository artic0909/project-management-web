{{--
    admin/leads/_validation_assets.blade.php
    jQuery validation for Lead Create and Edit forms.
--}}

<script>
$(document).ready(function() {
    const form = $('form[action*="leads"]');

    // Real-time numeric enforcement for phone fields
    $(document).on('input change keyup paste', 'input[name="phone[]"]', function() {
        let val = this.value.replace(/\D/g, '');
        if (this.value !== val) {
            this.value = val;
        }
    });

    form.on('submit', function(e) {
        let isValid = true;
        let firstErrorEl = null;
        const errors = [];

        function markError(el, msg) {
            isValid = false;
            if (!firstErrorEl) firstErrorEl = el;
            $(el).addClass('is-invalid');
            
            // Add error message below the field if not exists
            let errorSpan = $(el).siblings('.field-error');
            if (errorSpan.length === 0) {
                // If it's a multi-select, append to wrap
                if ($(el).closest('.ms-wrap').length > 0) {
                    errorSpan = $('<span class="field-error"></span>').appendTo($(el).closest('.ms-wrap'));
                } else {
                    errorSpan = $('<span class="field-error"></span>').insertAfter(el);
                }
            }
            errorSpan.text(msg).show();

            // Remove error on input
            $(el).one('input change', function() {
                $(el).removeClass('is-invalid');
                $(el).siblings('.field-error').fadeOut();
            });
        }

        // 1. Basic Required Fields
        const requiredFields = [
            { name: 'contact_person', label: 'Contact Person' },
            { name: 'business_type', label: 'Business Type' },
            { name: 'address', label: 'Full Address' }
        ];

        requiredFields.forEach(f => {
            const el = $(`[name="${f.name}"]`);
            if (!el.val() || el.val().trim() === '') {
                markError(el, `${f.label} is required.`);
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

        // 4. Service Need Validation (Multi-select)
        const services = $('input[name="service_ids[]"]:checked');
        if (services.length === 0) {
            isValid = false;
            const trigger = $('#serviceWrap .ms-trigger');
            trigger.css('border-color', '#ef4444');
            errors.push('At least one Service Need must be selected.');
            if (!firstErrorEl) firstErrorEl = trigger;
            
            $('input[name="service_ids[]"]').one('change', function() {
                trigger.css('border-color', '');
            });
        }

        if (!isValid) {
            e.preventDefault();
            
            if (firstErrorEl) {
                $('html, body').animate({
                    scrollTop: $(firstErrorEl).offset().top - 120
                }, 500);
                $(firstErrorEl).focus();
            }
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
