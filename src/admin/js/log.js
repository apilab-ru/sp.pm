window.log = new function(){
    var self = this;
    this.__proto__ = abase;
    
    this.clear = function(){
        self.send("/admin/ajax/logger/clear")
          .then(()=>{
              self.reload();
          })
    }
};

