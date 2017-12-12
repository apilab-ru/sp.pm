"use strict";
var basket = new function()
{
    var self = this;
    this.__proto__ = base;
    
    this.getParam = function($parent){
        var $box = $parent.parents('.p-stock__item:first');
        var size = ($box.find('.js-size')) ? $box.find('.js-size').val() : null;
        var color = ($box.find('.js-color')) ? $box.find('.js-color').val() : null;
        return {
           size  : size,
           color : color
        };
    }
    
    this.toCard = function(stock, myb)
    {
        var $parent = $(myb);
        $parent.addClass('p-stock__bayed');
        var old = parseInt($parent.find('.js-current').val());
        if(isNaN(old)){
            old = 0;
        }
        $parent.find('.js-current').val(++ old);
        self.update( stock, old, self.getParam($parent));
    }
    
    this.minus = function(stock, myb)
    {
        var $parent = $(myb).parents('.js-buy-but');
        var old = parseInt($parent.find('.js-current').val());
        old --;
        if(old < 1){
            old = 0;
            $parent.removeClass("p-stock__bayed");
        }
        $parent.find('.js-current').val(old);
        self.update( stock, old, self.getParam($parent) );
    }
    
    this.add = function(stock, myb)
    {
        var $parent = $(myb).parents('.js-buy-but');
        var old = parseInt($parent.find('.js-current').val());
        old ++;
        $parent.find('.js-current').val(old);
        self.update( stock, old, self.getParam($parent) );
    }
    
    this.update = function(stock, count, param)
    {
        self.post('catalog','updateBasket',{
            stock : stock,
            count : count,
            param : param
        }).then((mas)=>{
            self.updateViewCount( mas.count );
        })
    }
    
    this.updateViewCount = function(count)
    {
        $('.basket-count').html(count);
    }
    
    this.orderChange = function(stock, param, change, myb){
        var $loader = self.loader( myb );
        self.post('catalog','orderChange',{
            stock  : stock,
            param  : param,
            change : change
        }).then((stat)=>{
            if(stat.stat){
                navigation.reload();
            }else{
                throw(stat.error);
            }
        })
        .catch((e)=>{
            if(!e){
               e = "Ошибка, попробуйте позже"; 
            }
            popUp(e,'error');
            $loader.remove();
        })
    }
    
    this.orderDeleteItem = function(stock, param, myb){
        var $loader = self.loader( myb );
        self.post('catalog','deleteOrderItem',{
            stock  : stock,
            param  : param
        }).then((stat)=>{
            if(stat.stat){
                navigation.reload();
            }else{
                throw(stat.error);
            }
        })
        .catch((e)=>{
            if(!e || $.type(e) != 'string'){
               e = "Ошибка, попробуйте позже"; 
            }
            popUp(e,'error');
            $loader.remove();
        })
    }
}