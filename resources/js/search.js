$(function() {
    var $medicationSearch = $('#medication-search');
    var $medicationId = $('#medication-id');
    var $searchResults = $('#search-results');
    var items = $('#search-results .list-group-item');
    var selectedMedication = -1;

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
        focus: function(event, ui){
            event.preventDefault();
            $(this).val(ui.item.label);
        },
        select: function(event, ui) {
            $medicationId.val(ui.item.value);
            $medicationSearch.val(ui.item.label);

            return false;
        }
    });

    function updateActiveItem(){
        items.removeClass('active');
        if (selectedMedication >= 0) {
            items.eq(selectedMedication).addClass('active');
        }
    }

    $medicationSearch.on('keydown', function(e) {
        if (e.key === 40) { // ↓
            e.preventDefault();
            selectedMedication = (selectedMedication + 1) % items.length;
            updateActiveItem();
        } else if (e.key === 38) { // ↑
            e.preventDefault();
            if (selectedMedication > 0) {
                selectedMedication--;
            } else {
                selectedMedication = -1;
            }
            updateActiveItem();
        } else if (e.key === 13 && selectedMedication !== -1) { // Enter, show suggest
            e.preventDefault();
            $medicationSearch.val(items.eq(selectedMedication).text());
            $searchResults.hide();
            selectedMedication = -1; // reset
        }
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest($medicationSearch).length) {
            $searchResults.hide();
        }
    });
});
