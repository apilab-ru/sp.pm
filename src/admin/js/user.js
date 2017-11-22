var user = new function(){
    this.__proto__ = abase;
    var self = this;
    
    this.initEditForm = function(selector){
        var $box = $(selector);
        var $file = $box.find('.js-file-uploader') ;
        fileUploader.initOneUploader(
            $file   
        );
        
        $box.on('submit',function(event){
            event.preventDefault();
            var form = $box.serializeObject();
            var formData = new FormData();
			formData.append('photo', $file.getFiles());
            formData.append('send', JSON.stringify(form));
            
            self
            .sendFormData('/admin/ajax/users/save', formData)
            .then((status)=>{
                if(status.stat == 1){
                    return status;
                }else{
                    throw("Произошла ошибка");
                }
            })
            .then((status)=>{
                popUp("Всё ок");
                self.reload();
                $box.trigger("close");
            })
            .catch((error)=>{
                popUp(error);
            })
        })
    }
}