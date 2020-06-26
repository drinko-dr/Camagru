<?php
	get_header();

	if ($_GET[option2] == "settings")
		echo "settings";
	else
		echo "page";
	get_footer();
?>
