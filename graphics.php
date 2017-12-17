<?php
	header("Content-type: application/json; charset=utf-8");
	$files = array_values(array_diff(scandir("images/graphics"), array(".", "..", ".DS_Store")));
	echo json_encode($files);
?>