<?php


class db
{
    private static $_instance = null;

    private $db;

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new db();
        }
        return self::$_instance;
    }

    private function __construct() {
        // Формируем строку соединения с сервером
        $server = 'localhost';
        $user = 'gal_test';
        $pass = 'quot5Co7';
        $database = 'gal_test';

        $this->db = null;
        try {
            $this->db = new PDO(
                sprintf(
                    "mysql:host=%s;dbname=%s",
                    $server,
                    $database
                ),
                $user,
                $pass
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new Exception("There was a problem connecting. " . $e->getMessage());
        }
    }

    private function __sleep() {}
    private function __wakeup() {}
    private function __clone() {}

    public function Query($query) {
        $q = $this->db->prepare($query);
        $q->execute();

        if ($q->errorCode() != PDO::ERR_NONE) {
            echo $q->errorInfo().PHP_EOL;
            return false;
        }

        return $q->fetchAll();
    }
}
?>
