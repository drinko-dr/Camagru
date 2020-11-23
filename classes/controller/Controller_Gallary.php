<?php
namespace classes\controller;
include_once "classes/model/Model_Gallegy.php";


defined('INDEX') OR die('Прямой доступ к странице запрещён!');


class Controller_Gallary
{

	public static function action_index(){
		$model = new Model_Gallegy();

		$image = $model->getUserImageList(
			array(
				'per_page' => 6,
				'page' => $_GET['page']
			)
		);
		$gallary = array(
			'image' => $image,
			'page' => $_GET['page']
		);
		require_once "classes/view/gallary.php";
	}
}