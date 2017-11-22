"use strict";
var admin = new function () {
    
    this.__proto__ = abase;
    
    var self = this;

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
    
    this.addForm = function(link){
        self.send(link.replace('/admin/','/admin/ajax/'))
        .then(function(mas){
            var $win = self.createPop("Форма добавления",mas.html);
            self.initForm( $win, mas, link );
        });
    }
    
    this.editForm = function(link,id){
        self.send(link.replace('/admin/','/admin/ajax/'), {
            id : id
        })
        .then(function (mas) {
            var $win = self.createPop("Форма редактирования", mas.html);
            self.initForm($win, mas, link);
        });
    }
    
    this.initForm = function($win, mas, link){
        
        $win.find('form').on('close',function(){
            $win.remove();
        })
        
        //var link = link.split("/");
        /*$win.find('select').chosen();
        $.datetimepicker.setLocale('ru');
        $win.find('.datetime').each(function(n,i){
            $(i).datetimepicker({
                lang   : "ru",
                format : "d.m.Y H:i"
            });
        })*/
        
    }
    
    /*this.changeCheck = function(myb,id,field,link){
        var check = ($(myb).prop('checked')) ? 1 : 0;
        self.post(link.replace('/page/',''),{
            field : field,
            id    : id,
            check : check
        });
    }*/
    
    this.message = function(text){
        //alert(text);
    }
    
    this.deleteItem = function(myb,link,id){
        if(confirm("Вы уверены, что хотите удалить ?")){
            $(myb).parents('tr:first').remove();
            self.send(link.replace('/admin/','/admin/ajax/'),{id:id});
        }
    }
    
    this.setPage = function(page, event, myb)
    {
        event.preventDefault();
        var link = $(myb).attr('href');
        history.pushState('navigation','navigation',link);
        self.loader( $('.mainContent') );
        self.send( link.replace('/admin/','/admin/ajax/') )
            .then(function(mas){
                $('.mainContent').html(mas.html);
            });
    }
    
    this.setFilter = function(myb, event)
    {
        event.preventDefault();
        var url = $(myb).serialize();
        location.search = "?" + url;
        self.reload();
    }
};