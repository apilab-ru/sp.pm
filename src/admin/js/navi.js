"use strict";
var navi = new function(){
    var self = this;
    this.__proto__ = admin;
    
    this.go = function(link){
        history.pushState('navigation', 'navigation', link);
        self.loader( $('.mainContent') );
        self.send(link.replace('/admin/','/admin/ajax/'),{getmenu:1})
            .then(function(mas){
                $('.mainContent').html(mas.html);
                $('.navBox').html(mas.navi);
            })
            .catch(function(re){
                $('.mainContent').html(re);
            })
    }
};