$(document).on('click', '.faq-checkbox', function () {
    var $myb = $(this);
    if ($myb.hasClass('checked')) {
        $myb.removeClass('checked');
    } else {
        $myb.addClass('checked');
    }
    $myb.next().toggle("fast");
});