<?php
function smarty_modifier_pagejson($filter,$page,$plus = 0,$minus = 0){
	$filter['page'] = $page + $plus - $minus;
	return "filter=".json_encode($filter);
}
?>