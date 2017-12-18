(function(){
    window.messages = new function()
    {
        this.__proto__ = base;
        var self = this;
        //this.time = 0;
        
        this.connect = function(time)
        {
            //self.time = time;
            //self.poll();
            console.log('time', time);
            
            self.worker = new Worker("/build/worker.js");
            self.worker.addEventListener('message', function(e) {
                console.log('Worker said: ', e.data.data);
                if(e.data.action == 'message'){
                    var message = e.data.data;
                    if(self.getDialog() == message.from){
                        self.renderMessage(message.from, message.date, message.text);
                     }else{
                        popUp('Новое сообщение');
                        self.updateCount();
                    }
                }
            }, false);
            
            self.worker.postMessage({
                action : 'settime',
                time   : time
            });
        }
        
        this.updateCount = function()
        {
            var $box = $('.js-count-message');
            var val = parseInt($box.text());
            $box.html( val + 1 );
        }
        
        this.getDialog = function()
        {
            var match = location.pathname.match(`/messages/user/([0-9]*)/`);
            if(match){
                return match[1];
            }else{
                return 0;
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
        }
        
    }
})();