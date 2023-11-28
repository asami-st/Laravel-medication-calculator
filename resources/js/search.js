$(function() {
    var selectedMedication = -1;
    var $medicationId = $('#medication-id');
    var $searchResults = $('#search-results');
    var $medicationSearch = $('#medication-search');

    $medicationSearch.autocomplete({
        source: function(request, response) {
            $.ajax({
                url: '/prescritption/search-medications',
                dataType: 'json',
                data: {
                    search: request.term
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.label,
                            value: item.value
                        };
                    }));
                }
            });
        },
        minLength: 3,
        select: function(ui) {
            $medicationId.val(ui.item.value);
            $medicationSearch.val(ui.item.label);

            return false;
        }
    });

    $('#medication-search').on('keydown', function(e) {
        var items = $('#search-results .list-group-item');
        if (e.key === 40) { // ↓
            e.preventDefault();
            selectedMedication = (selectedMedication + 1) % items.length;
            items.removeClass('active');
            items.eq(selectedMedication).addClass('active');
        } else if (e.key === 38) { // ↑
            e.preventDefault();
            if (selectedMedication <= 0) {
                selectedMedication = -1;
                items.removeClass('active');// back to input field
            } else {
                selectedMedication--;
                items.removeClass('active');
                items.eq(selectedMedication).addClass('active');
            }
        } else if (e.key === 13 && selectedMedication !== -1) { // Enter, show suggest
            e.preventDefault();
            $('#medication-search').val(items.eq(selectedMedication).text());
            $searchResults.hide();
            selectedMedication = -1; // reset
        }
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#medication-search').length) {
            $searchResults.hide();
        }
    });
});
