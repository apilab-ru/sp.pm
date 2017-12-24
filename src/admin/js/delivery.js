(function(abase){
    window.delivery = new function()
    {
        var self = this;
        this.__proto__ = abase;
        
        //var link = "//api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU";
        
        this.mapInit = null;
        this.map     = null;
        /*this.initMap = function()
        {
            if(self.mapInit){
                return self.mapInit;
            }else{
                return self.mapInit = new Promise((resolve, reject)=>{
                    $.getScript(link,()=>{
                        self.map = new ymaps.Map ("map", {
                            center: [55.76, 37.64], 
                            zoom: 7
                        });
                       resolve(); 
                    });
                });
            }
        }*/
        
        this.initForm = function(selector)
        {
            var $box = $(selector);
            var timeout = null;
            $box.find('.js-address').on('input',function(){
                clearTimeout(timeout);
                var address = $(this).val();
                timeout = setTimeout(()=>{
                    self.getPoint(address)
                        .then((point)=>{
                            $box.find('.js-point').val( point );
                        });
                },1500);
            });
        }
        
        this.getPoint = function(address)
        {
            /*return self.initMap()
                .then(()=>{
                    
                });*/
        }
        
    }
})(abase);