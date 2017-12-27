{strip}
<div class="flex-line dostavka__art-box">
    <div class='article-text dostavka__art'>
        {$art.text}
    </div>
    <div class='pay-blockchain'>
        {foreach from=$steps item=item}
            <div class='pay-blockchain__item'>
                {$item.text}
            </div>
            <br>
        {/foreach}
    </div>
</div>
<div class="map-box" id="YMapsID"></div>
<script>
    events.trigger('loadYaMap',{
        center   : {$art.params|json_encode},
        delivers : {$delivers|json_encode}
    });        
</script>
{/strip}