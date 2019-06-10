<?php
class db
{
    private static $_instance = null;

    private $db; // Ресурс работы с БД

    /**
     * @return object of db class
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new db();
        }
        return self::$_instance;
    }

    /**
     * After we can't copy object of class db
     */
    private function __construct() {}
    private function __sleep() {}
    private function __wakeup() {}
    private function __clone() {}

    /**
     * This is connection to database
     * @param $user user of database
     * @param $password password for connect to database
     * @param $base name of database
     * @param string $host host connection database
     * @param int $port port of connection
     */
    public function Connect($user, $password, $base, $host = 'localhost', $port = 3306)
    {
        // String for connection
        $connectString = 'mysql:host=' . $host . ';port= ' . $port . ';dbname=' . $base . ';charset=UTF8;';
        $this->db = new PDO($connectString, $user, $password,
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // возвращать ассоциативные массивы
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // возвращать Exception в случае ошибки
            ]
        );
    }

    /**
     * @param $query sql request
     * @param array $params parameters for sql request, this is optional
     * @return execute sql request
     */
    public function Query($query, $params = array())
    {
        $res = $this->db->prepare($query);
        $res->execute($params);
        return $res;
    }

    /**
     * @param $query sql request
     * @param array $params parameters for sql request, this is optional
     * @return array with selected from database values
     */
    public function Select($query, $params = array())
    {
        $result = $this->Query($query, $params);
        if ($result) {
            return $result->fetchAll();
        }
    }

    /**
     * $query sql request
     * @param array $params parameters for sql request, this is optional
     * @return row from database
     */
    public function SelectRow($query, $params = array())
    {
        $result = $this->Query($query, $params);
        if ($result) {
            return $result->fetchAll()[0];
        }
    }
		
}

