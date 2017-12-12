"use strict";
var auth = new function(){
    var self = this;
    
    this.__proto__ = base;
    
    this.showAuth = function(act){
        
        var $back = $("<div>",{
            class : "backModal"
        });
        
        $back.append( $('.js-form-reg-auth').clone() ); 
        
        $back.on('click','.reg-auth-close',function(){
            $back.remove();
        })
        
        var mySelect = function (act){
            $back.find('.menu-checked').removeClass('menu-checked');
            $back.find('.menu-item[act="'+ act +'"]').addClass('menu-checked');
            $back.find('.req-auth__item.checked').removeClass('checked');
            $back.find('.req-auth__item[act="'+ act +'"]').addClass('checked');
        }
        
        $back.on('click','.menu-item',function(){
            mySelect( $(this).attr('act') );
        })
        
        mySelect(act);
        
        $('body').append($back);
    }
    
    this.out = function(){
        self.post('auth','out')
            .then(function(){
                location.reload();
            }).catch((e)=>{
                popUp(e,'error');
            });
    }
    
    this.auth = function(myb, event){
        event.preventDefault();
        var form = self.serialise( $(myb) );
        self.post('auth','login',form)
            .then(function(mas){
                location.reload();
            }).catch(function(e){
                $(myb).find('.error').html( e.error );
            });
    }
    
    
    
    this.reg = function(myb, event){
        event.preventDefault();
        var form = self.serialise( $(myb) );
        self.post('auth','reg',form)
            .then(function(mas){
                location.reload();
            }).catch(function(e){
                $(myb).find('.error').html( e.error );
            });
    }
}

$(document).on('click','.js-reg,.js-auth-in',function(){
    
    if($(this).hasClass('js-reg')){
        var act = 'reg';
    }else{
        var act = 'auth';
    }
    
    auth.showAuth(act);
})

$(document).on('click','.js-auth-out',function(){
    auth.out();
});