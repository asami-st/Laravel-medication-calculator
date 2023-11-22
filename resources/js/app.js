import './bootstrap';

// import '..css/app.css';


import 'select2';
$(function () {
    $('.medication-select').select2({
        placeholder: "Select Medication",
        allowClear: true,
        minimumInputLength: 1,
        language: "ja"
    });
});
