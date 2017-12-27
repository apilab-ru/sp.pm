(function(){
    "use strict";
    window.customInput = new function()
    {
        var self = this;

        this.renderCustom = function(name){
            return `<div class="cust-list__item js-item">
                    <span class='js-remove remove-icon'></span>
                    <span class='js-name cust-list__item-name'>${name}</span>
                </div>`
        }

        this.initCustomList = function( $box )
        {
            var $add = $box.find('.js-button-add');
            var $inp = $box.find('.js-edit-name');

            $add.on('click',function(){
                var name = $inp.val();
                $inp.val("");
                $( self.renderCustom(name) ).insertBefore( $box.find('.js-add-control') );
            });

            $box.on('click','.js-remove',function(){
                $(this).parents('.js-item').remove();
            })

            $box.getList = function(){
                var list = [];
                $box.find('.js-name').each(function(n,i){
                    list.push( $(i).text().trim() );
                });
                return list;
            }

            return $box;
        }

    };
})();