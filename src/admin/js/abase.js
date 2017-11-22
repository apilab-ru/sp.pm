"use strict";
var abase = new function(){
    
    this.createPop = function(title,html){
        var $div = $("<div class='popWindow'>");

        $div.html(
                "<div class='title'>"+ title +"</div>" +  
                "<div class='close btn btn-danger'><i class='icon-remove icon-white'></i></div>" +            
                "<div class='body'>"+ html +"</div>"     
                );

        var $box = $('.boxPop');
        if(!$box.length){
            $box = $('<div class="boxPop"><div class="dialogs"></div></div>'); 
            $('body').append( $box ); 
        }

        $box.find('.dialogs').append( $div );

        var width = $div.outerWidth();
        $box.css({
            left : (screen.width - width) / 2 
        });
        $box.on('click','.close',function(){
            $box.remove();
        })
        
        $div.draggable({handle:".title"});
        
        return $div;
    };
    
    this.post = function (controller, action, send) {
        return new Promise((resolve, reject) => {
            $.post('/ajax/' + controller + "/" + action + "/",{
                send : send
            },function(mas){
                if(mas.stat){
                    resolve(mas);
                }else{
                    reject(mas);
                }
            })
        });
    }
    
    this.send = function(url, send){
        return new Promise((resolve, reject) => {
            $.post(url,{
                send : send
            },function(mas){
                resolve(mas);
            })
        });
    }
    
    this.sendFormData = function(url, formData){
        return new Promise((resolve, reject)=>{
            var xhr = new XMLHttpRequest();
            xhr.onload = xhr.onerror = function () {
                try{
                    var res = JSON.parse(this.response);
                    resolve(res);
                }catch(e){
                    var res = {};
                    console.error('error send', this.response);
                    reject(this.response);
                }
            };

            xhr.open("POST", url, true);
            xhr.send(formData);
        });
    }
    
    this.reload = function()
    {
        navi.go(location.pathname + location.search);
    }
    
    this.loader = function($div)
    {
        var $loader = $("<div class='loader__boxed'><div class='loader'></div></div>")
        $div.append($loader);
        return $loader;
    }
    
}

//var popUpMessage = null;

var popUp = function(text){
    
    var $box = $('.js-popup');
    if($box.length){
        $box.data('cansel')();
        $box.removeClass('active');
    }else{
        $box = $('<div/>',{
            class : 'js-popup popup-box'
        });
        $('body').append($box);
    }
    
    var $remove = $('<span/>',{
        class : "remove-icon"
    })
    
    $remove.on('click',function(){
        $box.remove();
    })
    
    $box.append($remove);
    $box.html(text);
    
    $box.addClass('active').show();
    
    var timeout = setTimeout(function(){
        $box.removeClass('active').hide();
    },10000);
    
    $box.data('cansel',function(){
        clearTimeout(timeout);
    })
    
}