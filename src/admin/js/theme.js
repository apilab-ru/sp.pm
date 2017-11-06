$(function() {
    $(document).on('click','.hide-sidebar',function () {
        $('#sidebar').hide('fast', function () {
            $('#content').removeClass('span10');
            $('#content').addClass('span12');
            $('.hide-sidebar').hide();
            $('.show-sidebar').show();
        });
    });

    $(document).on('click','.show-sidebar',function () {
        $('#content').removeClass('span12');
        $('#content').addClass('span10');
        $('.show-sidebar').hide();
        $('.hide-sidebar').show();
        $('#sidebar').show('fast');
    });
});