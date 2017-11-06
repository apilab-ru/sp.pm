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

;var fileUploader = new function () 
{
    this.calcSize = function (image, a) {
        var img = image,
                w = img.width, h = img.height,
                s = w / h;
        if (w > a && h > a) {
            if (img.width > img.height) {
                img.width = a;
                img.height = a / s;
            } else {
                img.height = a;
                img.width = a * s;
            }
        }

        return img;
    }
    
    this.makePreview = function(file){
        var fr = new FileReader();
        fr.readAsDataURL(file);
        return new Promise((resolve, reject) => {
            fr.onload = (function (event) {
                resolve(event.target.result)
            });
        });
    }
    
    this.returnSrc = function(img, tpl)
    {
        return "/cachephoto" + img.folder + img.name + "_" + tpl + "." + img.type;
    }
}