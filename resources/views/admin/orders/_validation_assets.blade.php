{{--
    admin/orders/_validation_assets.blade.php
    jQuery validation for Order Create and Edit forms.
--}}

<script>
$(document).ready(function() {
    const form = $('form[action*="orders"]');

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

        // Clear previous custom error borders on multi-selects
        $('.ms-trigger').css('border-color', '');
        $('.field-error').hide();
        $('.is-invalid').removeClass('is-invalid');

        function markError(el, msg) {
            isValid = false;
            if (!firstErrorEl) firstErrorEl = el;
            $(el).addClass('is-invalid');
            
            // Find or create error span
            let errorSpan = $(el).siblings('.field-error');
            if (errorSpan.length === 0) {
                if ($(el).closest('.ms-wrap').length > 0) {
                    errorSpan = $('<span class="field-error"></span>').appendTo($(el).closest('.ms-wrap'));
                } else if ($(el).parent().is('.form-row')) {
                    errorSpan = $('<span class="field-error"></span>').appendTo($(el).closest('.form-row'));
                } else {
                    errorSpan = $('<span class="field-error"></span>').insertAfter(el);
                }
            }
            errorSpan.text(msg).show();

            // Remove error on input
            $(el).one('input change', function() {
                $(el).removeClass('is-invalid');
                errorSpan.fadeOut();
            });
        }

        // 1. Basic Required Fields
        const requiredFields = [
            { name: 'company_name', label: 'Company Name' },
            { name: 'client_name', label: 'Client Name' },
            { name: 'domain_name', label: 'Domain Name' },
            { name: 'order_value', label: 'Order Value' },
            { name: 'payment_terms_id', label: 'Payment Terms' },
            { name: 'delivery_date', label: 'Delivery Date' },
            { name: 'city', label: 'City' },
            { name: 'state', label: 'Region / State' },
            { name: 'zip_code', label: 'Zip Code' },
            { name: 'full_address', label: 'Full Address' },
            { name: 'status_id', label: 'Order Status' },
            { name: 'transaction_date', label: 'Payment Date' },
            { name: 'amount', label: 'Amount Received' },
            { name: 'payment_method', label: 'Payment Mode' }
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

        // 4. Multi-select Validation (Services & Sources)
        const msFields = [
            { id: 'serviceWrap', name: 'service_ids[]', label: 'Service / Product' },
            { id: 'sourceWrap', name: 'source_ids[]', label: 'Lead Source' }
        ];

        msFields.forEach(ms => {
            const checked = $(`input[name="${ms.name}"]:checked`);
            if (checked.length === 0) {
                const wrap = $(`#${ms.id}`);
                const trigger = wrap.find('.ms-trigger');
                trigger.css('border-color', '#ef4444');
                
                let err = wrap.find('.field-error');
                if (err.length === 0) {
                    err = $('<span class="field-error"></span>').appendTo(wrap);
                }
                err.text(`${ms.label} is required.`).show();
                
                if (!firstErrorEl) firstErrorEl = trigger;
                isValid = false;

                $(`input[name="${ms.name}"]`).one('change', function() {
                    trigger.css('border-color', '');
                    err.fadeOut();
                });
            }
        });

        // 5. Zip Code Digit Check
        const zipField = $('input[name="zip_code"]');
        if (zipField.val() && zipField.val().length !== 6) {
            markError(zipField[0], 'Zip Code must be exactly 6 digits.');
        }

        if (!isValid) {
            e.preventDefault();
            
            if (firstErrorEl) {
                $('html, body').animate({
                    scrollTop: $(firstErrorEl).offset().top - 150
                }, 500);
                // Attempt to focus
                setTimeout(() => { $(firstErrorEl).focus(); }, 600);
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
    .ms-wrap .field-error {
        margin-top: 8px;
    }
</style>
