<?php
/*
 * *nix pipe handler
 */
$input_stream = fopen("php://stdin","r");
while($line = fgets($input_stream))
{
	$array = json_decode($line,true);
	$array = aws_converter($array);
	print(json_encode($array)."\n");
}
fclose($input_stream);


/*
 * conversion function from string attributes to proper JSON.
 * there will be cases for the other two AWS attribute types, not addressed here.
 */
function aws_converter($old) {
	foreach($old as $k=>$v) {
		if(!empty($v['s']) && substr($v['s'], 0, 1) == "{") { 
			$new[$k] = json_decode($v['s'], true);
		}
		else { $new[$k] = $v['s']; }
	}
	return $new;
}
?>
