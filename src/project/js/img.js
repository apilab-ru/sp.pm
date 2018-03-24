;(function(){
    
    window.img = new function(){ 
        
        this.list = [];
        
        var self = this;
        
        construnct();
        
        this.load = function(myb){
            self.list.push(myb);
        } 
        
        function construnct(){
            setInterval(()=>{
                self.loadFromList()
            }, 100);
        }
        
        this.loadFromList = function(){
            if(self.list.length > 0){
                var item = $(self.list.shift());
                var link = item.attr('link');
                item.attr({'src':link, 'link':null});
            }
        }
        
        this.initBox = function($div){
            $div.find('img[link]').each((n,i)=>{
               var item = $(i);
               item.attr({src: item.attr('link')})
            });
        }
    }
    
})();