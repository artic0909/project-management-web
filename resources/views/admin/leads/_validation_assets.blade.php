{{--
    admin/leads/_validation_assets.blade.php
    jQuery validation for Lead Create and Edit forms.
--}}

<script>
$(document).ready(function() {
    const form = $('#leadCreateForm');
    
    if (form.length === 0) {
        // Fallback for edit page if it doesn't use the ID yet
        console.warn('Lead form by ID not found, falling back to action selector.');
        form = $('form[action*="leads"]');
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
        // First, check if we should even proceed
        let isValid = true;
        let firstErrorEl = null;

        // Clear previous errors immediately
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

        // Validation Rules
        const contactPerson = $('[name="contact_person"]');
        if (!contactPerson.val() || contactPerson.val().trim() === '') {
            markError(contactPerson, 'Contact Person is required.');
        }

        // Email Validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        $('input[name="email[]"]').each(function() {
            const val = $(this).val().trim();
            if (val !== '' && !emailRegex.test(val)) {
                markError(this, 'Please enter a valid email address.');
            }
        });

        // Phone Validation
        let hasPhone = false;
        $('input[name="phone[]"]').each(function() {
            const val = $(this).val().trim();
            if (val !== '') {
                hasPhone = true;
                if (val.length < 7) {
                    markError(this, 'Phone number is too short.');
                }
            }
        });

        if (!hasPhone) {
            markError($('input[name="phone[]"]').first(), 'At least one Phone Number is required.');
        }

        // Service Need Validation
        if ($('input[name="service_ids[]"]:checked').length === 0) {
            markError($('input[name="service_ids[]"]').first(), 'At least one Service Need must be selected.');
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

        // SUCCESS: Show loading state
        submitBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Saving...');
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
