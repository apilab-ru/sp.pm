var catalog = new function()
{
    var self = this;
    this.__proto__ = abase;
    
    this.initEditPurchase = function(selector){
        var $box = $(selector);
        $box.find('select').chosen();
        CKEDITOR.replace('editDescr');
        $box.on('submit',function(event){
            event.preventDefault();
            var form = $box.serializeObject();
            form.form.description = CKEDITOR.instances.editDescr.getData();
            self.send('/admin/ajax/catalog/savePurchase',form)
            .then((stat)=>{
                if(stat.stat){
                    return 1;
                }else{
                    throw(stat.error);
                }
            })
            .then(()=>{
                popUp("Успешно");
                self.reload();
                $box.trigger("close");
            })
            .catch((error)=>{
                popUp(error);
            })
        });
    }
    
    this.initEditStock = function(selector){
        var $box = $(selector);
        $box.find('select').chosen();
        CKEDITOR.replace('editDescr');
        
        var $file = $box.find('.js-file-uploader') ;
        var $list = $box.find('.js-list');
        fileUploader.initListUploader(
            $file, $list   
        );
        
        var $sizes  = customInput.initCustomList( $box.find('.js-sizes') );
        var $colors = customInput.initCustomList( $box.find('.js-colors') );
        
        $box.on('submit',function(event){
            event.preventDefault();
            
            var form = $box.serializeObject().form;
            form.description = CKEDITOR.instances.editDescr.getData();
            
            form.files = [];
            var files = $list.data('getFiles')();
            
            var formData = new FormData();
			
            var n=0;
            $.each(files,function(n,i){
                if(i.id){
                    form.files.push( i.id );
                }else{
                    formData.append('file['+ n +']', i);
                    n++;
                }
            });
            
            form.colors = $colors.getList();
            form.sizes  = $sizes.getList();
            
            formData.append('form', JSON.stringify(form));
            self
            .sendFormData('/admin/ajax/catalog/saveStock', formData)
            .then((status)=>{
                if(status.stat == 1){
                    return status;
                }else{
                    throw("Произошла ошибка ");
                }
            })
            .then(()=>{
                popUp("Успешно");
                self.reload();
                $box.trigger("close");
            })
            .catch((error)=>{
                popUp(error);
            })
            
        });
    }
    
};