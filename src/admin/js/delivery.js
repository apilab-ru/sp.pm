(function(abase){
    "use strict";
    window.delivery = new function()
    {
        var self = this;
        this.__proto__ = abase;
        
        this.mapInit = null;
        this.map     = null;
        
        this.initEditPageDelivery = function()
        {
            CKEDITOR.replace('deliveryPageText');
            var timeoutUpdate;
            $('#steps').nestable({maxDepth: 1})
                .on('change', (e) => {
                    clearTimeout(timeoutUpdate);
                    setTimeout(() => {
                        
                        var list = [];
                        var order = 0;
                        $('#steps li').each((n,i)=>{
                            order++;
                            
                            var $item = $(i);
                            
                            list.push({
                                id    : $item.attr('data-id'),
                                order : order
                            });
                        });
                        
                        self.post('main','updateOrderSteps',{
                            list : list
                        }).then(()=>{
                            popUp("Порядок Блоков обновлён");
                        }).catch((e)=>{
                            console.error(e);
                            popUp("Ошибка сохранения порядка блоков",'error');
                        });
                    }, 700);
                });
        }
        
        events.add('editPageDelivery',()=>{
            self.initEditPageDelivery();
        });
        
        this.deleteStep = function(id, myb)
        {
            $(myb).parents('li').remove();
            self.post('main','deleteStep',{
                id : id
            });
        }
        
        this.savePageText = function()
        {
            var $loader = self.loader($('.js-delivery-page-text'));
            var text = CKEDITOR.instances['deliveryPageText'].getData();
            self.post('main','saveTextPageDelivery',{
                text : text
            }).then(()=>{
                $loader.remove();
                popUp("Успешно");
            }).catch(()=>{
                $loader.remove();
                popUp("Ошибка","error");
            })
            
        }
        
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
                        })
                        .catch(()=>{
                            $box.find('.js-point').val("");
                        });
                },1500);
            });
        }
        
        this.getPoint = function(address)
        {
            var url = "https://geocode-maps.yandex.ru/1.x/?format=json&geocode=";
            
            address = address.replace(" ","+");
            
            return self.get(url+address+"&results=1")
                .then((data)=>{
                    var point = data.response.GeoObjectCollection.featureMember[0].GeoObject.Point.pos;
                    point = point.split(" ");
                    return point[1] +", "+point[0];
                })
                .catch((e)=>{
                    popUp("Адресс " + address + " не найден","error");
                    throw(e);
                });
        }
        
    }
})(abase);