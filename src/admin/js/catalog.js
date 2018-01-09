var catalog = new function()
{
    var self = this;
    this.__proto__ = abase;
    
    this.initEditPurchase = function(selector){
        var $box = $(selector);
        $box.find('select').chosen();
        CKEDITOR.replace('editDescr');
        
        var $file = $box.find('.js-file-uploader') ;
        var $list = $box.find('.js-list');
        fileUploader.initListUploader(
            $file, $list   
        );
        
        $box.find('.js-text-option').each((n,i)=>{
            var myid  = $(i).attr('id');
            CKEDITOR.replace(myid,{
                height : 100,
                toolbar : [
                    { name: 'document', items: [ 'Source' ] },
                    { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord',  'Undo', 'Redo' ] },
                    { name: 'insert', items: [ 'Table', 'HorizontalRule' ] },
                    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                    { name: 'colors', items: [ 'TextColor', 'BGColor' ] }
                ]
            });
        });
        
        $box.on('submit',function(event){
            event.preventDefault();
            event.stopPropagation();
            var form = $box.serializeObject();
            
            $box.find('.js-text-option').each((n,i)=>{
                var name = $(i).attr('myname');
                var myid = $(i).attr('id');
                form.option[name] = CKEDITOR.instances[myid].getData();
            });
            
            console.log('form', form);
            
            form.form.description = CKEDITOR.instances.editDescr.getData();
            
            var formData = new FormData();
            form.option[9] = [];
            
            var files = $list.data('getFiles')();
            
            var n=0;
            $.each(files,function(n,i){
                if(i.id){
                    form.option[9].push( i.id );
                }else{
                    formData.append('file['+ n +']', i);
                    n++;
                }
            });
            
            formData.append('form', JSON.stringify(form));
            
            self.sendFormData('/admin/ajax/catalog/savePurchase', formData)
                //self.send('/admin/ajax/catalog/savePurchase',form)
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
            event.stopPropagation();
            
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
    
    this.initEditCats = function(){
    {
        var timeoutUpdate;
            
        $('#cats').nestable()
            .on('change',(e)=>{
                clearTimeout(timeoutUpdate);
                setTimeout(()=>{
                    var tree = $('#cats').nestable('serialize');
                    var list = self.getItemTree([], tree, 0);
                    self.send('/admin/ajax/catalog/catsListUpdate',{
                        list : list
                    })
                }, 700)
            })
    }
    
    this.deleteCat = function(id, myb)
    {
        if(confirm("Удалить категорию ? Вложенные категории будут сброшены в корневой уровень")){
            var $parent = $(myb).parents('.js-item');
            var $loader = self.loader( $parent );
            self.send('/admin/ajax/catalog/catRemove',{id:id})
            .then((stat)=>{
                if(stat.stat){
                    $parent.remove();
                    return stat;
                }else{
                    throw(stat.error);
                }
            })
            .catch((e)=>{
                popUp(e);
                $loader.remove();
            })
        }
    }
    
    this.editCat = function(id)
    {
        var $box = $('.js-cat-edit');
        self.loader( $box );
        self.send('/admin/ajax/catalog/catEdit',{id:id})
            .then((mas)=>{
                $box.html(mas.html);
                var $file = $box.find('.js-file-uploader') ;
                fileUploader.initOneUploader($file);
                
                $box.on('submit','.js-form',function(event){
                    event.preventDefault();
                    self.saveCat( $(this), $file );
                });
            })
            .catch((e)=>{
                $box.html(e);
            });
    }
    
    this.saveCat = function($form, $file)
    {
        var form = $form.serializeObject();
        var formData = new FormData();
        formData.append('file', $file.getFiles());
        formData.append('send', JSON.stringify(form));

        var $loader = self.loader( $('.js-cat-edit') );

        self
        .sendFormData('/admin/ajax/catalog/catSave', formData)
        .then((status)=>{
            if(status.stat == 1){
                return status;
            }else{
                throw("Произошла ошибка");
            }
        })
        .then((status)=>{
            admin.reload();
        })
        .catch((e)=>{
            popUp(e);
        })
        .then(()=>{
           $loader.remove(); 
        })
    }
}
    
};