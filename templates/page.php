<?php

	if ($_GET[option2] == "settings"){
		require_once ('./includes/user-settings.php');
		get_header();
		include './templates/template-user-settings.php';
	}
	else{
		get_header();
		echo "page";
		get_footer();
	}
?>

