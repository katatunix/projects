<?php

$a = array(1,2,3);

foreach ($a as $i => $v)
{
	if ($i == 1)
	{
		unset($a[$i]);
	}
}

print_r($a);

?>
