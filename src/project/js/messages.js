(function(){
    window.messages = new function()
    {
        this.__proto__ = base;
        var self = this;
        //this.time = 0;
        
        this.eventOpenDialog = function()
        {
            var $lastElem = $('.dialog__message-item:last');
                
            if($lastElem.length){
                var top = $lastElem.offset().top;
            }else{
                var top = 0;
            }

            $('.dialog__box').animate({
                scrollTop: top
            },200);
            self.updateCount();
            
            setTimeout(()=>{
               $('.not-readed').removeClass('not-readed');
            },1000);
        }
        
        this.openedDialogs = function()
        {
            return location.pathname == "/messages/dialogs/";
        }
        
        this.connect = function(time)
        {
            self.worker = new Worker("/build/worker.js");
            self.worker.addEventListener('message', function(e) {
                if(e.data.action == 'message'){
                    var message = e.data.data;
                    message.from = parseInt(message.from);
                    if(self.getDialog() === message.from){
                        self.renderMessage(message.from, message.text, message.date);
                        self.setRead( message.id );
                     }else{
                        if(self.openedDialogs()){
                            navigation.reload();
                        }else{
                            self.updateCount();
                            popUp('Новое сообщение');
                        }
                    }
                }
            }, false);
            
            self.worker.postMessage({
                action : 'settime',
                time   : time
            });
        }
        
        this.setRead = function(messageId)
        {
            return self.post("messages", 'setRead',{
                'id' : messageId
            });
        }
        
        this.updateCount = function()
        {
            var $box = $('.js-count-message');
            self.post('messages','getCount')
                .then((data)=>{
                    $box.text( data.count );
                });
        }
        
        this.getDialog = function()
        {
            var match = location.pathname.match(`/messages/user/([0-9]*)/`);
            if(match){
                return parseInt(match[1]);
            }else{
                return false;
            }
        }
        
        this.submit = function(myb, e, user)
        {
            e.preventDefault();
            e.stopPropagation();
            var $myb = $(myb);
            var text = $myb.find('.js-text').val();
            var $loader = self.loader( $myb );
            self.sendMessage(user, text)
                .then((stat)=>{
                    $myb.find('.js-text').val("");
                    var d = new Date(); 
                    var date = d.getYear()+"-"+d.getMonth()+"-"+d.getDay()+" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
                    self.renderMessage(window.userId, text, date);
                })
                .catch((e)=>{
                    popUp(e.error,'error');
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
        
        this.renderMessage = function(from, text, date)
        {
            var clas =(window.userId == from) ? "dialog__from" : "dialog__to";
            var user = (window.userId == from) ? window.userName : window.dialogUser;
            $('.dialog__list').append(
                `<div class='dialog__message-item ${clas}'>
                    <div class="flex-line">
                        <div class="dialog__message-date">${date}</div>
                        <div class="dialog__message-user"> 
                            ${user}
                        </div>
                    </div>
                    <div class="dialog__message-text">${text}</div>
                </div>`
            );
    
            $('.dialog__box').animate({
                scrollTop : $('.dialog__message-item:last').offset().top 
            });
        }
        
        init();
        function init(){
            events.add('openDialog',function(){
                self.eventOpenDialog();
            });
        }
    }
})();