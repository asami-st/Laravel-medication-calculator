$(function(){
    $('#master-checkbox').on("click", function(){
        $('.child-checkbox').prop('checked', this.checked);
    });
});
