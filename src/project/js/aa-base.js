"use strict";
;var base = new function(){
   
   this.serialise = function($form){
       var form = {};
       $form.find('input,select').each(function(n,i){
           var $i = $(i);
           form[ $i.attr('name') ] = $i.val();
       })
       return form;
   }
   
    this.send = function (url, send) {
        return new Promise((resolve, reject) => {
            $.post(url, {
                send: send
            }, function (mas) {
                if (mas) {
                    resolve(mas);
                } else {
                    reject("Ошибка сети");
                }
            })
        });
    }
   
   this.post = function (controller, action, send) {
        if(!send){
            send = {};
        }
        //send.ja = true;
        return new Promise((resolve, reject) => {
            this.xchr = $.post('/ajax/' + controller + "/" + action + "/",{
                send : send
            },function(mas){
                /*var res = re.split('<ja>');
                if (res[1] != undefined) {
                    var text = res[0];
                    res = res[1].split('</ja>');
                    text += res[1];
                    re = text;
                    var mas = $.parseJSON(res[0]);
                } else {
                    var mas = re;
                }*/
                
                if(mas.stat){
                    resolve(mas);
                }else{
                    reject(mas);
                }
            })
        });
    }
    
    this.loader = function(box){
        var $loader = $("<div class='loader__boxed'><div class='loader'></div></div>")
        $(box).append($loader);
        return $loader;
    }
    
    this.loaderProress = function(box)
    {
        var $loader = $('<div class="loader__box"><div curpercent="0" class="loader__process"></div></div>');
        
        var $load = $loader.find('.loader__process');
        
        var loader = setInterval(function () {
            var curPerc = parseInt($load.attr('curpercent'));
            curPerc += 5;
            if (curPerc > 100) {
                curPerc = 100;
            }
            $load.css({'width': curPerc + "%"}).attr({'title': curPerc + "%"});
            if (curPerc == 100) {
                curPerc = -5;
            }
            $load.attr({'curpercent': curPerc});
        }, 500);
        
        $loader.day = function () {
            clearInterval(loader);
            $load.parent().remove();
        }
        
        $loader.stop = function () {
            clearInterval(loader);
            $load.css({'width': '100%'});
            setTimeout(function () {
                $load.remove();
            }, 1000);
        };
        
        $(box).append($loader);
        
        return $loader;
    }
    
    this.sendFile = function(url, file, send, $status){
        return new Promise((resolve, reject) => {
            var formData = new FormData();
			formData.append('file', file);
            
            if(send){
               formData.append('send', JSON.stringify(send)); 
            }
            
            $status.show();
            var xhr = new XMLHttpRequest();
            if($status){
                xhr.upload.onprogress = function (event) {
                    var w = Math.ceil(event.loaded / event.total * 100)  + "%";
                    $status.animate({width: w})
                }
            }

            xhr.onload = xhr.onerror = function () {
                $status.hide();
                //console.log('response', this.response);
                try{
                    var res = JSON.parse(this.response);
                }catch(e){
                    var res = {};
                    console.log('res', this.response);
                }
                resolve(res);
            };

            xhr.open("POST", url, true);
            xhr.send(formData);
        });
    }
    
    this.modalWin = function(html)
    {
        var $back = $("<div>",{
            class : "backModal"
        });
        
        var $div = $('<div/>',{
            class : 'modal-win'
        })
        
        $div.append("<span class='reg-auth-close'>X</span>");
        $div.append(html);
        
        $back.append( $div ); 
        
        $back.on('click','.reg-auth-close',function(){
            $back.remove();
        })
       
        $('body').append($back);
    }
   
}

function popUp(text){
    alert(text);
}