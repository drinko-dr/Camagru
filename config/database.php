<?php
namespace config;
use PDO;

defined('INDEX') OR die('Прямой доступ к странице запрещён!');
define(CONFIG, include $_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
class DataBase{

    private $link;
    /**
     * DataBase constructor.
     */
    public function __construct($setup = ""){
        $this->connect($setup);
    }

    /**
     * Подключение к базе данных
     * @return $this
     */
    private function connect($setup){
        if ($setup == "")
        {
            $dsn = 'mysql:host='.CONFIG['host'].';dbname='.CONFIG['db_name'].';charset='.CONFIG['charset'];
        }
        else if ($setup == "db"){
            $dsn = 'mysql:host='.CONFIG['host'];
        }
        try {
            $this->link = new PDO($dsn, CONFIG['username'], CONFIG['password']);
            $this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }
        return $this;
    }

    /**
     * Добавление новых полей в таблице базы данных
     * @param $sql
     * @return array
     */
    protected function execute($sql, $param){
        $sth = $this->link->prepare($sql);

        return $sth->execute($param);
    }

    /**
     * Запрос вывода значений поля базы данных
     * @param $sql
     * @return array
     */
    public function query($sql, $param){
        $sth = $this->link->prepare($sql);

        $sth->execute($param);

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        if($result === false)
            return [];

        return $result;
    }

    /**
     * @param $user
     * @return array
     */
    public function getUser($user){
        return $this->query("SELECT * FROM `cm_users` WHERE `login`= ?", array($user));
    }
    /**
     * @param $user
     * @return array
     */
    public function getUserByEmail($email){
        return $this->query("SELECT * FROM `cm_users` WHERE `email`= ?", array($email));
    }

    /**
     * @param $user
     * @return array
     */
    public function getUserByNickName($name){
        return $this->query("SELECT * FROM `cm_users` WHERE `name`= ?", array($name));
    }



    public function getCurrentUser(){
        return $this->getUser($_SESSION["login"]);
    }

    /**
     * @param $userEmail
     * @return boolean
     */
    public function checkUserEmail($email){
        $email = $this->getUserByEmail($email);
        if ($email != NULL)
            return true;
        else
            return false;
    }
    /**
     * @param $user
     * @param $password
     * @return int|num 0 - User allready used; 1 - User add; -1 - some error;
     */
    public function setUser($user, $email, $password){
        if ($this->getUser($user)[0]['login'] != NULL){
            return "Пользователь c таким ником уже существует";
        }
        else{
            $this->execute("INSERT INTO `cm_users` SET `login`= ?,`name`=?, `email`=?, `password`= ?", array($user, $user, $email, hash("whirlpool", $password )));
            return "Пользователь зарегестрирован!";
        }
    }

    /**
     * @param $id - пользователь
     * @param $key
     * @param $value
     *
     * установка мета информации пользователя
     */
    public function setUserMeta($id, $key, $value){
        if ( empty($this->getUserMeta($id, $key)) )
            $this->execute("INSERT INTO `cm_user_meta` SET `user_id`= ?, `meta_key`=?, `meta_value`=?", array($id, $key, $value));
        else
            $this->execute("UPDATE `cm_user_meta` SET `meta_value` = ? WHERE `cm_user_meta`.`user_id`= ? AND `cm_user_meta`.`meta_key` = ?", array($value, $id, $key));
    }

    /**
     * @param $id
     * @param $key
     * @return string значение
     *
     * получение мета информации пользователя
     */
    public  function getUserMeta($id, $key){
        $value = $this->query("SELECT * FROM `cm_user_meta` WHERE `user_id`= ? AND `meta_key`= ?", array($id, $key));
        return $value[0]['meta_value'];
    }

    /**
     * @param $id
     * @param $key
     *
     * удаление мета
     */

    public function delUserMeta($id, $key){
        $this->execute("DELETE FROM `cm_user_meta` WHERE `cm_user_meta`.`user_id`= ? AND `cm_user_meta`.`meta_key`= ?", array($id, $key));
    }
    /**
     * @param $updatePwd
     * @return boolean
     */
    public function upDatePass($login, $new_pwd){
        $this->execute("UPDATE `cm_users` SET `password` = ? WHERE `cm_users`.`login` = ?", array($new_pwd, $login));
    }

    /**
     * @param $updateUser
     * @return boolean
     */
    public function upDateUser($name, $email, $new_pwd){
        if ($new_pwd != null)
            $this->execute("UPDATE `cm_users` SET `name` = ?, `email` = ?, `password` = ? WHERE `cm_users`.`login` = ?", array($name, $email, $new_pwd, $_SESSION['login']));
        else
            $this->execute("UPDATE `cm_users` SET `name` = ?, `email` = ? WHERE `cm_users`.`login` = ?", array($name, $email, $_SESSION['login']));
    }



    /**
     * @return array
     */
    public function getAllusers(){
        return $this->query("SELECT * FROM `cm_users`");
    }

}

?>
