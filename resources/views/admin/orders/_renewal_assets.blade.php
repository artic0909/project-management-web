<script>
    function getCalculatedRenewalDate(monthsToAdd) {
        const today = new Date();
        const target = new Date(today.getFullYear(), today.getMonth() + monthsToAdd, today.getDate());
        const y = target.getFullYear();
        const m = String(target.getMonth() + 1).padStart(2, '0');
        const d = String(target.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    function formatRenewalDisplayDate(dateStr) {
        if (!dateStr) return '';
        const parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        const date = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const d = String(date.getDate()).padStart(2, '0');
        const m = months[date.getMonth()];
        const y = date.getFullYear();
        return `${d} ${m} ${y}`;
    }

    function handleRenewalTypeChange() {
        const type = $('#renewalTypeSelect').val();
        const dateInput = $('#renewalDateInput');
        const dateRow = $('#renewalDateRow');
        const preview = $('#renewalPreviewText');
        const previewDate = $('#renewalPreviewDate');

        if (type === 'one_time' || !type) {
            dateInput.val('');
            dateRow.hide();
            preview.hide();
        } else if (type === 'one_month') {
            const d = getCalculatedRenewalDate(1);
            dateInput.val(d);
            dateRow.hide();
            previewDate.text('Renewal Date: ' + formatRenewalDisplayDate(d));
            preview.css('display', 'flex');
        } else if (type === 'one_year') {
            const d = getCalculatedRenewalDate(12);
            dateInput.val(d);
            dateRow.hide();
            previewDate.text('Renewal Date: ' + formatRenewalDisplayDate(d));
            preview.css('display', 'flex');
        } else if (type === 'custom') {
            preview.hide();
            dateRow.show();
        }
    }

    $(document).ready(function() {
        if ($('#renewalTypeSelect').length > 0) {
            const currentType = $('#renewalTypeSelect').val();
            if (currentType === 'one_month' || currentType === 'one_year') {
                let val = $('#renewalDateInput').val();
                if (!val) {
                    val = currentType === 'one_month' ? getCalculatedRenewalDate(1) : getCalculatedRenewalDate(12);
                    $('#renewalDateInput').val(val);
                }
                $('#renewalPreviewDate').text('Renewal Date: ' + formatRenewalDisplayDate(val));
                $('#renewalPreviewText').css('display', 'flex');
                $('#renewalDateRow').hide();
            } else if (currentType === 'custom') {
                $('#renewalDateRow').show();
                $('#renewalPreviewText').hide();
            } else {
                $('#renewalDateRow').hide();
                $('#renewalPreviewText').hide();
            }
        }
    });
</script>
