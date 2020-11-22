<?php
get_header();
if ($_GET["option2"] == "settings"){
	require_once ('./includes/user-settings.php');
	require_once './templates/template-user-settings.php';
}
else
	echo "page";
get_footer();

?>

