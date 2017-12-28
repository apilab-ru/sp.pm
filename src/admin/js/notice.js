(function(){
    window.notice = new function()
    {
        this.__proto__ = abase;
        var self = this;
        this.saveNotice = function(myb, event)
        {
            event.preventDefault();
            event.stopPropagation();
            var $loader = self.loader( $(myb) );
            var form = $(myb).serializeObject();
            self.post('notice','saveAccount', form)
                .then(()=>{
                    $loader.remove();
                    popUp('Успешно');
                })
                .catch((e)=>{
                    $loader.remove();
                    console.log('e', e);
                    var error = e;
                    if(e.error){
                        error = e.error;
                    }
                    popUp(error,'error');
                })
        }
    }
})();