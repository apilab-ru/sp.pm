"use strict";
var admin = new function () {
    var self = this;

    this.post = function (controller, action, send) {
        return new Promise((resolve, reject) => {
            $.post('/ajax/' + controller + "/" + action + "/",{
                send : send
            },function(mas){
                if(mas.stat){
                    resolve(mas);
                }else{
                    reject(mas);
                }
            })
        });
    }

    this.auth = function (my, event) {
        if(event){
            event.preventDefault();
        }
        var form = $(my).serializeObject();
        
        self.post('auth','login',form).then(function(mas){
            location.reload();
        }).catch(function(e){
            alert(e.error);
        });
    }
    
    this.storage = {};
    
    this.setStorage = function(data){
        self.storage = data;
    }
    
    this.getStorage = function(name){
        return self.storage[name];
    }
};