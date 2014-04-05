<?php /**/ ?><?php
	$json_file = "content.json";
	$json = json_decode(file_get_contents($json_file));
	
	$section = $json->$_POST["section"];
	$page = $section[$_POST["page"]];
	
	$left = $page->left;
	$right = $page->right;
	
	echo json_encode(array("left"=>$left, "right"=>$right));
	
?>