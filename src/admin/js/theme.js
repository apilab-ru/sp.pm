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
    
    $(document).on('click','.nav-list li.top',function(event){
        event.preventDefault();
        $(this).parents('.nav-list')
                .find('.active')
                .removeClass('active');
        $(this).addClass('active');
    })

    $(document).on('click','.nav-list li.top ul a',function(event){
        event.preventDefault();
        event.stopPropagation();
        var $a = $(this);
        var $li = $a.parents('li:first');
        $li.addClass('active');
        $li.siblings('.active').removeClass('active');

        if($a.attr('href')){
            navi.go( $a.attr('href') );
        }
    })
    
    $(document).on('click','.js-toggle-open',function(){
        if( $(this).hasClass("open") ){
            $(this).removeClass("open");
        }else{
            $(this).addClass("open");
        }
    })
});