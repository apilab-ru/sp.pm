"use strict";
var catalog = new function(){
    var self = this;
    this.__proto__ = base;
    this.load = null;
    this.xchr = null;
    
    this.init = function()
    {
        $('.js-limit').on('change',function(){
            clearTimeout( self.load );
            self.load = setTimeout(function(){
                self.reload();
            },500);
        })
        
        $('.catalog__filter').on('change','input,select',function(){
            clearTimeout( self.load );
            self.load = setTimeout(function(){
                self.reload();
            },10);
        })
        self.reload(1);
    }
    
    this.reload = function(page)
    {
        if(self.stateLoad){
            self.stateLoad.remove();
        }
        self.stateLoad = self.loader( '.js-catalog-content' );
        if(self.xchr){
            self.xchr.abort();
        }
        var form = $('.catalog__filter').serializeObject();
        form.page  = page;
        form.limit = $('.js-limit').val();
        
        self.post('catalog','dataList',form)
        .then(function(mas){
            $('.js-catalog-content').html(mas.html);
            $('.js-pagination').html(mas.pagination);
        })
    }
    
    this.setPurchaseFavorite = function(purchase, myb)
    {
        var check = ($(myb).prop('checked')) ? 1 : 0;
        self.post('catalog','setPurchaseFavorite',{
            set      : check,
            purchase : purchase
        })
        .then((stat)=>{
            popUp(stat.message);
        })
        .catch((stat)=>{
            $(myb).prop('checked', (check) ? false : true)
            popUp(stat.error);
        });
    }
    
    this.showModal = function(href)
    {
        self.modalWin( "<img src='"+ href +"'/>" );
    }
}

$(function(){
    if(window.initCatalog){
        catalog.init();
    }
    
    $(document).on('click','.js-modal',function(event){
        event.preventDefault();
        event.stopPropagation();
        catalog.showModal( $(this).attr('href') );
    })
})


