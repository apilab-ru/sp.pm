{strip}
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left"> Место доставки #{$object.id} </div>
        </div>
        <div class="block-content">
            {assign var=myid value=$smarty.now|cat:"order":$object.id}
            <form class="form-horizontal" method="post" id="{$myid}" action="/admin/main/saveDelivery">
                <fieldset>
                    <div class="flexLine">
                        {if $object}
                            <input type="hidden" name="form[id]" value="{$object.id}"/>
                        {/if}
                        <div class="flexRow">
                            <div class="control-group">
                                <label class="control-label">Адресс </label>
                                <div class="controls">
                                    <input type="text" class="js-address" placeholder="Кинель, Улица, номер дома" name="form[address]" value="{$object.address}"/>
                                </div>
                            </div> 
                        </div>  
                        <div class="flexRow">
                            <div class="control-group">
                                <label class="control-label">Координаты </label>
                                <div class="controls">
                                    <input type="text" class="js-point" placeholder="X, Y" name="form[point]" value="{$object.point}"/>
                                </div>
                            </div> 
                        </div>        
                        <div class="flexRow">
                            <div class="control-group">
                                <label class="control-label">Описание </label>
                                <div class="controls">
                                     <input type="text" placeholder="Кинель, Улица, номер дома" name="form[descr]" value="{$object.descr}"/>
                                </div>
                            </div>
                       </div>
                    </div>
                    <div class="form-actions submitLine">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>    
                </fieldset>
            </form>
        </div>
        <script>
           $(function(){
               delivery.initForm("#{$myid}");
           });
        </script>
    </div>
{/strip}