import './bootstrap';
import '../css/app.css';



import 'select2';
$(function () {
    $('.medication-select').select2({
        allowClear: true,
        minimumInputLength: 1,
        language: "ja"
    });
});
$(document).ready(function() {
    $('.medication-select').select2();
});


