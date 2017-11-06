<?php
function smarty_modifier_morph($value, $word0, $word1, $word2) {
	if (preg_match('/1\d$/', $value)) { 
		return $word2; 
	} elseif (preg_match('/1$/', $value)) { 
		return $word0;
	} elseif (preg_match('/(2|3|4)$/', $value)) { 
		return $word1; 
	} else { 
		return $word2; 
	} 
}
?>