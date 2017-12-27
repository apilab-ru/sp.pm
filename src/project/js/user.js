"use strict";
var user = new function()
{
    var self = this;
    this.__proto__ = base;
    
    this.initCabinet = function(){
        
        var $box = $('.js-user-cab-photo');
        var $statusText = $box.find('.js-status-text');
        var $process = $box.find('.js-process');
        var $img = $box.find('.js-photo');
        var $input = $box.find('.js-input');
        
        $input.on('change',function(){
            var file = this.files[0];
            $(this).val("");
            self.uploadUserPhoto(file, $img, $process);
        })
        
    }
    
    this.updatePass = function(myb, event)
    {
        event.preventDefault();
        var form = $(myb).serializeObject();
        
        console.log('form', form);
        
        if(form.pass1 != form.pass2){
            popUp("Введёные пароли не совпадают",'error');
        }else{
            var loader = self.loader(myb);
            self.post('users','updatePass',{
                pass : form.pass1
            })
            .then((stat)=>{
                popUp('Успешно');
                $(myb).parents('.backModal').remove();
            })
            .catch((e)=>{
                loader.remove();
                popUp(e,'error');
            });
        }
    }
    
    this.uploadUserPhoto = function(file, $img, $process){
        fileUploader
            .makePreview(file)
            .then((url)=> {
                $img.css({'backgroundImage' : "url("+url+")"});
            });
            
        self
            .sendFile('/ajax/images/add/', file, {
                parent: 'user'
            }, $process)
            .then((status)=>{
                var url = fileUploader.returnSrc(status, '168x198x3');
                $img.css({
                    'backgroundImage' : "url(" + url + ")"});
                
            }).catch((error)=>{
                console.log('error', error);
            })
    }
    
    if(window.initCabinet){
        self.initCabinet();
    }
     
}