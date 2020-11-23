<?php
get_header();
include_once "classes/model/Model_Gallegy.php";
$model = new Model_Gallegy();
$img = $model->getUserImageList();
var_dump($img[2]['comments']->rates);
echo "page";
get_footer();

?>

