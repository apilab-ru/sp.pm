(function(base){
    
    window.navigation = new function()
    {
        var self = this;
        
        this.__proto__ = base;
        
        this.clickMenu = function(myb, e)
        {
            e.preventDefault();
            e.stopPropagation();
            var $parent = $(myb).parent();
            $parent.addClass('menu-checked');
            $parent.siblings('.menu-checked').removeClass('menu-checked');
            
            var url = $(myb).attr('href');
            
            self.go(url);
        }
        
        this.goLink = function(url, e)
        {
            e.preventDefault();
            e.stopPropagation();
            $('.menu-checked').removeClass('menu-checked');
            self.go(url);
        }
        
        this.goMenu = function(url, e, original)
        {
            e.preventDefault();
            e.stopPropagation();
            $('.menu-checked').removeClass('menu-checked');
            if(!original){
                original = url;
            }
            $('.menu-item a[href="'+ original +'"]').parent().addClass('menu-checked');
            self.go(url);
        }
        
        this.go = function(url){
            self.loader( $('.content') );
            history.pushState('navigation', 'navigation', url);
            self.send(url,{
                page : 'ajax'
            }).then((res)=>{
                $('.content').html(res);
            }).catch(()=>{
                $('.content').html("Произошла ошибка, обновите страницу");
            });
        }
        
        self.reload = function(){
            self.loader( $('.content') );
            //self.go( location.pathname + location.search );
            self.send(location.pathname + location.search,{
                page : 'ajax'
            }).then((res)=>{
                $('.content').html(res);
            }).catch(()=>{
                $('.content').html("Произошла ошибка, обновите страницу");
            });
        }
        
    }
    
})(base);

$(function(){
    
    $(document).on('click','a',function(event){
        var href = $(this).attr('href');
        if(href[0] == "/" && href != '/admin/'){
            navigation.goLink(href, event);
        }
    })
    
    window.onpopstate = function(event) {
        navigation.reload();
    };
    
});