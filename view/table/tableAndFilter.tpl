{strip}
<div class='flexLine'>
    <div class='flexRow'>
        {$table}
    </div>
    <div class='filterBox'>
        <div class='block'>
            <div class='navbar navbar-inner block-header'>
                <div class='muted pull-left'>  Фильтер </div>
            </div>
            <div class='block-content'>
                {$filter}
            </div>
        </div>
    </div>
    <script>
        $(function(){
            apilab.initForm( $('html') );
        })
    </script>
</div>
{/strip}