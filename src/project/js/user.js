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