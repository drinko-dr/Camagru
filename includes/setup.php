<?php
defined('INDEX') OR die('Прямой доступ к странице запрещён!');
//require_once './config/database.php';
class Setup extends DataBase {
private $install = false;

    public function __construct(){
    	if ($this->install === true) {
			$this->createDataBase();
			$this->createDataTable();
			$this->finishInstall();
		}else
    		return;
    }

	/**
	 *	Create Data base
	 */
	private function createDataBase(){
        try {
        	$createDB = new DataBase("db");

            $createDB->execute("CREATE DATABASE IF NOT EXISTS ".CONFIG['db_name']." CHARACTER SET utf8 COLLATE utf8_general_ci");

			$createDB = NULL;

        } catch (PDOException $e) {
            die("DB ERROR: ". $e->getMessage());
        }

    }

	/**
	 *	Create data tables
	 */
	private function createDataTable(){
		try {
			$createDT = new DataBase();

			$createDT->execute(
				"CREATE TABLE `cm_users` (
                    id int(11) NOT NULL AUTO_INCREMENT,
                    login varchar(24) NOT NULL,
                    name varchar(24) NOT NULL,
                    email varchar(255) NOT NULL,
                    password varchar(255) NOT NULL,
                        PRIMARY KEY  (id),
                        KEY name (login)
                        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;");

			$createDT->execute("CREATE TABLE `cm_options` (
                    id int(11) NOT NULL AUTO_INCREMENT,
                    name varchar(255) NOT NULL,
                    value varchar(255) NOT NULL,
                        PRIMARY KEY  (id)
                        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;");

			$createDT->execute("CREATE TABLE `cm_user_meta` (
                    id int(11) NOT NULL AUTO_INCREMENT,
                    user_id int(11) NOT NULL,
                    meta_key varchar(255) NOT NULL,
                    meta_value varchar(255) NOT NULL,
                        PRIMARY KEY  (id)
                        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;");

			$createDT = NULL;

		} catch (PDOException $e) {
			die("DB ERROR: ". $e->getMessage());
		}
	}

	/**
	 *	Void function to finish instalation
	 */
	private function finishInstall(){
		$line = 5; // номер строки, которую нужно изменить
		$replace = "private \$install = false;"; // на что нужно изменить
		$file = file(__FILE__);
		$file[$line - 1] = $replace.PHP_EOL;
		file_put_contents(__FILE__, join('', $file));
	}
}
new Setup();