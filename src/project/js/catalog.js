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
    
    this.purchaseInit = function()
    {
        self.purchaseReload();
    }
    
    this.changeStockCat = function(myb)
    {
        var check = $(myb).prop('checked');
        if( $(myb).val() == "0" ){
            if(check){
                $('.js-cat').prop('checked', false);
            }
        }else{
            if(check){
                $('.js-cat-all').prop('checked', false);
            }
        }
        self.purchaseReload();
    }
    
    this.purchaseReload = function()
    {
        var send = {
            purchase : window.initPurchase
        };
        $('.js-catalog-filter input').each(function(n,i){
            send[ $(i).attr('name') ] = $(i).val();
        });
        send.cats = [];
        $('.js-catalog-cats input').each(function(n,i){
            if( $(i).prop("checked") ){
                send.cats.push( $(i).val() );
            }
        })
        if(self.xchr){
            self.xchr.abort();
        }
        
        self.loader('.js-catalog-content');
        self.post('catalog','stockList',send)
        .then((mas)=>{
            $('.js-catalog-content').html(mas.html);
            $('.js-catalog-content').find('.custom-select').chosen({disable_search:true});
        });
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
    
    this.createOrder = (myb, event, id)=>
    {
        var $item = $(myb);
        event.preventDefault();
        event.stopPropagation();
        var $parent = $item.parents('.order__zakupka:first');
        var $loader = self.loader( $parent );
        self.send("/ajax/catalog/orderCreate/",{
            id : id
        })
          .then((data)=>{
            if(!data.stat){
                throw(data.error);
            }else{
                $loader.remove();
                navigation.reload();
            }  
          })
          .catch((e)=>{
              $loader.remove();
              popUp(e);
          });
    }
    
    this.canselOrder = (myb, id)=>
    {
        if( confirm("Вы уверены что хотите отказаться от Покупки ? Часть акций возможно станут недоступны, и вы не получите скидку.") ){
            var $loader = self.loader( myb );
            self.send('/ajax/catalog/canselOrder/',{
                id : id
            }).then((data)=>{
                if(!data.stat){
                    throw(data.error);
                }else{
                    navigation.reload();
                    popUp("Закуака отменена, товары возвращены в корзину");
                }
            }).catch((e)=>{
                $loader.remove();
                popUp(e,'error');
            })
        }
    }
    
    this.sendPayReport = (myb, event)=>
    {
        event.preventDefault();
        var form = $(myb).serializeObject();
        var $loader = self.loader( $(myb) );
        self.post('catalog','sendPayReport',form)
            .then((data)=>{
                $loader.remove();
                popUp('Успешно');
            })  
            .catch((e)=>{
                popUp(e.error,'error');
                $loader.remove();
            })
    }
}

$(function(){
    if(window.initCatalog){
        catalog.init();
    }
    
    if(window.initPurchase){
        catalog.purchaseInit();
    }
    
    $(document).on('click','.js-modal',function(event){
        event.preventDefault();
        event.stopPropagation();
        catalog.showModal( $(this).attr('href') );
    })
})


