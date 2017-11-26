;var fileUploader = new function () 
{
    var self = this;
    
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
    
    this.initOneUploader = function($box)
    {
        var file;
        
        $box.find('.file-uploader__title').on('click',function(){
            $box.find('.js-file').click();
        });
        
        $box.find('.js-file').on('change',function(){
            file = this.files[0];
            self
            .makePreview(file)
            .then((url)=>{
                $box.find('.js-uploader-img').attr({src:url});
            })    
        })
        
        $box.data('getFiles',function(){
            return file;
        });
        
        $box.getFiles = function(){
           return file; 
        }
          
        return $box;
    }
    
    this.initListUploader = function($box, $list)
    {
        var $file = $box.find('.js-file');
        
        $list.data('getFiles', function(){
            
            var list = [];
            
            $list.find('li').each(function(n,i){
                var $i = $(i);
                if($i.attr('myid')){
                    list.push({id:$i.attr('myid')});
                }else{
                    list.push( $i.data('file') );
                }
            })
            
            return list;
        })
        
        $file.on('change',function(){
            $.each(this.files,function(n, file){
                self
                .makePreview(file)
                .then((url)=>{
                    
                    var $item = $(
                        "<li class='js-file-item'>\
                            <span class='icon-remove icon-white remove-icon js-remove'></span>\
                            <img src='"+ url +"'/>\
                        </li>"
                    );
                    $item.data('file', file);
                    
                    $item.on('click','.js-remove',function(){
                        $item.remove();
                    })
                    
                    $list.append( $item );
                });
            });
        })
    }
    
    this.removeThisFile = function(myb)
    {
        $(myb).parents('.js-file-item:first').remove(); 
    }
};