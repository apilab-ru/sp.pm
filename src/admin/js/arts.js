var arts = new function()
{
    var self = this;
    this.__proto__ = abase;
    
    this.initEditList = function ()
    {
        var timeoutUpdate;
        $('#arts').nestable({maxDepth:2})
            .on('change', (e) => {
                clearTimeout(timeoutUpdate);
                setTimeout(() => {
                    var tree = $('#arts').nestable('serialize');
                    var list = self.getItemTree([], tree, 0);
                    self.send('/admin/ajax/main/updateArtsTree',{
                        list : list
                    });

                }, 700)
            })
    }
    
    this.initFormEdit = function(selector)
    {
        var $form = $(selector);
        var myid = $form.find('textarea').attr('id');
        CKEDITOR.replace(myid);
        
        $form.on('submit',function(event){
            event.preventDefault();
            event.stopPropagation();
            var form = $form.serializeObject();
            form.form.text = CKEDITOR.instances[myid].getData();
            
            var $loader = self.loader( $form );
            
            self.send('/admin/ajax/main/saveArt',form)
            .then((stat)=>{
                if(stat.stat){
                    self.reload();
                    $form.parents('.popWindow').remove();
                }else{
                    throw(stat.error);
                }
            })
            .catch((e)=>{
                popUp(e,'error');
                $loader.remove();
            })
        });
    }
    
    this.deleteArt = function(id, myb)
    {
        if(confirm("Вы уверены что хотите удалить статью ?")){
            var $parent = $(myb).parents('.dd-item:first');
            var $loader = self.loader( $parent );
            self.send('/admin/ajax/main/deleteArt',{id : id})
            .then((stat)=>{
                if(stat.stat){
                    self.reload();
                }else{
                    throw(stat);
                }
            })
            .catch((e)=>{
                $loader.remove();
                popUp(e,'error');
            })
        }
    }
    
};