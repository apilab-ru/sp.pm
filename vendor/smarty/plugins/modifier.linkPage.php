<?php
function smarty_modifier_linkPage($block) 
{
    return 'block_'.$block['id'].'_page';
}
