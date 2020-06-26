<?php
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
//            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); не удается подключиться с атрибутами
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
    protected function execute($sql){
        $sth = $this->link->prepare($sql);

        return $sth->execute();
    }

    /**
     * Запрос вывода значений поля базы данных
     * @param $sql
     * @return array
     */
    private function query($sql){
        $sth = $this->link->prepare($sql);

        $sth->execute();

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
        return $this->query("SELECT * FROM `cm_users` WHERE `login`='".$user."'");;
    }

    /**
     * @param $userEmail
     * @return boolean
     */
    public function checkUserEmail($email){
        $email = $this->query("SELECT * FROM `cm_users` WHERE `email`='".$email."'");
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
            $this->execute("INSERT INTO `cm_users` SET `login`='".$user."', `email`='".$email."', `password`='".MD5($password)."'");
            return "Пользователь зарегестрирован!";
        }
        return "error";
    }

    /**
     * @return array
     */
    public function getAllusers(){
        return $this->query("SELECT * FROM `cm_users`");
    }

}

?>
