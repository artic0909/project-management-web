{{--
    admin/orders/_validation_assets.blade.php
    jQuery validation for Order Create and Edit forms.
--}}

<script>
$(document).ready(function() {
    const form = $('#orderCreateForm');
    
    if (form.length === 0) {
        form = $('form[action*="orders"]');
    }

    const submitBtn = form.find('button[type="submit"]');

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

        // Clear previous errors
        $('.field-error').remove();
        $('.is-invalid').removeClass('is-invalid');
        $('.ms-trigger').css('border-color', '');

        function markError(el, msg) {
            isValid = false;
            
            let target = $(el);
            let scrollTarget = target;

            if (target.hasClass('phone-num-inp')) {
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
            
            if (target.closest('.ms-wrap').length > 0) {
                errorSpan.appendTo(target.closest('.ms-wrap'));
            } else if (target.hasClass('phone-num-inp')) {
                errorSpan.insertAfter(target.closest('.phone-wrap'));
            } else {
                errorSpan.insertAfter(target);
            }

            $(el).one('input change', function() {
                if (target.hasClass('phone-num-inp')) {
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
        const msFields = [
            { id: 'serviceWrap', name: 'service_ids[]', label: 'Service / Product' },
            { id: 'sourceWrap', name: 'source_ids[]', label: 'Lead Source' }
        ];

        msFields.forEach(ms => {
            if ($(`input[name="${ms.name}"]:checked`).length === 0) {
                markError($(`input[name="${ms.name}"]`).first(), `${ms.label} is required.`);
            }
        });

        // 5. Zip Code Specific Check
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
        submitBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Finalizing...');
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
