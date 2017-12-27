(function(){
    "use strict";
    window.map = new function()
    {
        var self = this;
        
        this.init = function(data)
        {
            return self.load()
                .then((ym)=>{
                    var myMap = new ym.Map('YMapsID', {
                        center: [50.612053, 53.225204],//data.center,
                        zoom: 10
                    }, {
                        searchControlProvider: 'yandex#search'
                    });
                    
                    var objects = self.prepareObjects(data.delivers);
                    
                    var geoObjects = ym.geoQuery(objects)
						.addToMap(myMap)
						.applyBoundsToMap(myMap, {
							checkZoomRange: true
						});
                    
                });
        }
        
        this.loaded = false;
        
        this.load = function()
        {
            if(self.loaded){
               return self.loaded;
            }else{
                var url = "https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;coordorder=longlat";
                return self.loaded = new Promise((resolve,reject)=>
                {
                    $.ajax({
                        url: url,
                        dataType: "script",
                        success: function() {
                            ymaps.ready()
                                .done(function (ym) {
                                    resolve(ym);
                                });
                        },
                        error : (error)=>{
                            reject(error);
                        }
                    });
                });
            }
        }
        
        this.prepareObjects = function(delivers)
        {
            var data = {
                type : "FeatureCollection",
                features : []
            }
            $.each(delivers,(n, delivery)=>{
                data.features.push({
                    "type": "Feature",
                    "geometry": {
                        "type": "Point",
                        "coordinates": delivery.point
                    },
                    "properties": {
                        "balloonContent" : delivery.address + "<br>" + delivery.descr,
                        "hintContent"    : "#" + delivery.id + " " + delivery.address
                    },
                    "options": {
                        "preset": "islands#violetDotIcon"
                    }
                }); 
            });
            return data;
        }
        
        events.add('loadYaMap',(data)=>{
           self.init(data); 
        });
    }
})();