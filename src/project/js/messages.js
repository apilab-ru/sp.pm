(function(){
    window.messages = new function()
    {
        this.__proto__ = base;
        var self = this;
        
        this.connect = function(){
            
        }
        
        this.connect();
        
        this.submit = function(myb, e, user)
        {
            e.preventDefault();
            e.stopPropagation();
            var $myb = $(myb);
            var text = $myb.find('.js-text').text();
            var $loader = self.loader( $myb );
            self.sendMessage(user, text)
                .then((stat)=>{
                    
                })
                .catch((e)=>{
                    popUp(e,'error');
                })
                .then(()=>{
                    $loader.remove();
                });
        }
        
        this.sendMessage = function(user, message)
        {
            return self.post('messages','sendToUser',{
                user    : user,
                message : message
            })
            .then((data)=>{
                if(!data.stat){
                    throw(data.error);
                }else{
                    return data;
                }
            });
        }
        
        
    }
})();