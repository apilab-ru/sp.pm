(function(event){
    "use strict";
    //event.importScripts('https://code.jquery.com/jquery-3.2.1.min.js');

    event.addEventListener('message', function(e) {
        //console.log('message send', e.data.time);
        worker.connect(e.data.time);
    });

    var worker = new function(){

        var self = this;
        this.time = 0;
        this.isConnect = false;
        //this.__proto__ = base;
        
        this.post = function(controller, action, send)
        {
            return new Promise((resolve, reject)=>{
                
                var formData = new FormData();
                formData.append("send", JSON.stringify(send));
                var xhr = new XMLHttpRequest();
                xhr.open("POST", `/ajax/${controller}/${action}/`);
                xhr.send(formData);
                
                xhr.onreadystatechange = function() { 
                    if (xhr.readyState != 4) return;
                    if (xhr.status == 200) {
                        var data = JSON.parse(xhr.responseText);
                        resolve(data);
                    } else {
                        //console.log('e', xhr.readyState, xhr.status);
                        reject(xhr.responseText);
                    }
                }
            });
        }
        
        this.connect = function(time)
        {
            if(time > self.time){
                self.time = time;
            }
            if(!self.isConnect){
                self.isConnect = true;
                self.poll()
                    .catch((e)=>{
                       console.log('error connect', e); 
                    });
            }
        }

        this.poll = function()
        {
            return self.post('messages', 'server', {
                time: self.time
            }).then((data)=>{
                if(data.message){
                    self.time = data.message.time;
                    //console.log('time', self.time);
                    event.postMessage({
                        action : 'message', 
                        data   : data.message
                    });
                }
                return self.poll(); 
            });
        }

    }

})(self);