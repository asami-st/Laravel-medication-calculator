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
        select: function(event, ui) {
            // 選択された項目のIDを隠しフィールドに設定
            $medicationId.val(ui.item.value);
            // サジェストのテキストを検索ボックスに設定
            $medicationSearch.val(ui.item.label);
            // デフォルトのサジェストイベントをキャンセル
            return false;
        }
    });


    $('#medication-search').on('keydown', function(e) {
        var items = $('#search-results .list-group-item');
            if (e.keycode === 40) { // ↓
                e.preventDefault();
                selectedMedication = (selectedMedication + 1) % items.length;
                items.removeClass('active');
                items.eq(selectedMedication).addClass('active');
            } else if (e.keycode === 38) { // ↑
                e.preventDefault();
                if (selectedMedication <= 0) {
                    selectedMedication = -1;
                    items.removeClass('active');// back to inpu
                } else {
                    selectedMedication--;
                    items.removeClass('active');
                    items.eq(selectedMedication).addClass('active');
                }
            } else if (e.keycode === 13 && selectedMedication !== -1) { // Enter, show suggest
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

// $(function() {
//     var $medicationSearch = $('#medication-search');
//     var $medicationId = $('#medication-id');

//     // サジェスト機能の設定
//     $medicationSearch.autocomplete({
//         source: function(request, response) {
//             $.ajax({
//                 url: '/prescritption/search-medications',
//                 dataType: 'json',
//                 data: {
//                     search: request.term
//                 },
//                 success: function(data) {
//                     response($.map(data, function(item) {
//                         return {
//                             label: item.label,
//                             value: item.value
//                         };
//                     }));
//                 }
//             });
//         },
//         select: function(event, ui) {
//             // 選択された項目のIDを隠しフィールドに設定
//             $medicationId.val(ui.item.value);

//             // サジェスチョンのテキストを検索ボックスに設定
//             $medicationSearch.val(ui.item.label);

//             // デフォルトのサジェスチョンイベントをキャンセル
//             return false;
//         }
//     });
// });


        // if (searchValue.length >= 3) {
        //     $.getJSON('/prescription/search-medications', { search: searchValue }, function(medications) {
        //         $searchResults.empty().show();

        //         medications.slice(0, 5).forEach(function(medication) {
        //             $searchResults.append(
        //                 $('<a href="#" class="list-group-item list-group-item-action"></a>')
        //                     .text(medication.name + ' ' + medication.form + ' ' + medication.strength)
        //                     .data('id', medication.id) // idをdata属性に保存
        //                     .on('click', function(e) {
        //                         e.preventDefault();
        //                         $('#medication-search').val($(this).text());
        //                         $medicationId.val($(this).data('id')); // クリック時にidをhiddenフィールドに設定
        //                         $searchResults.hide();
        //                     })
        //             );
        //         });
        //     });
        // } else {
        //     $searchResults.hide();
        // }
