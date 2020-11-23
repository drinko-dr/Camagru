<?php
namespace classes\controller;
use config\DataBase;

defined('INDEX') OR die('Прямой доступ к странице запрещён!');
//include_once "config/database.php";

class Model_Gallegy
{

	private $db = NULL;

	public function __construct()
	{
		$this->db = new DataBase();
	}

	public function getUserImageList($arg = array('per_page' => 5, 'page' => 0)){
		if ($arg['page'] == 1)
			$arg['page'] = 0;
		$page = $arg['per_page'] * $arg['page'];
		if ($this->db != NULL){
			$user_id = $this->db->getUserByNickName($_GET['option']);
			$imgList = $this->db->query("SELECT * FROM `cm_gallary` WHERE `user_id` = ? LIMIT " . $page . ", ".$arg['per_page'], array($user_id[0]['id']));
			$total = $this->db->query("SELECT COUNT(1) FROM `cm_gallary` WHERE `user_id` = ? ", array($user_id[0]['id']));

		}

		foreach ($imgList as $key => $value){
			$imgList[$key]['comments'] = json_decode($value['comments']);
		}

		if ( !empty($total[0]) )
			$imgList['total_page'] = $total[0]['COUNT(1)'] / $arg['per_page'];

		$this->db = NULL;
		return $imgList;
	}

}