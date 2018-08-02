<?php
error_reporting(0);
class Core
{
    public $dbh; // handle of the db connexion
    private static $instance;

    private function __construct()
    {
        // building data source name from config
        $dsn = 'mysql:host=' . Config::read('db.host') .
               ';dbname='    . Config::read('db.basename');
        // getting DB user from config
        $user = Config::read('db.user');
        // getting DB password from config
        $password = Config::read('db.password');

        try
    {
        $this->dbh = new PDO($dsn, $user, $password);
        $this->dbh->exec('SET character_set_results=utf8');
        $this->dbh->exec('SET character_set_connection=utf8');
        $this->dbh->exec('SET character_set_client=utf8');
        $this->dbh->exec('SET collation_connection=utf8_general_ci');
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error handling
        return $this->dbh;
    }
    catch(PDOException $e)
    {
        die("Hel");
        echo "It seems there was an error.  Please refresh your browser and try again. ".$e->getMessage();
    }
    }

    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            $object = __CLASS__;
            self::$instance = new $object;
        }
        return self::$instance;
    }

    }

class Config
{
    static $confArray;

    public static function read($name)
    {
        return self::$confArray[$name];
    }

    public static function write($name, $value)
    {
        self::$confArray[$name] = $value;
    }

}

Config::write('db.host', "localhost");
Config::write('db.basename', "process_new");
Config::write('db.user', "root");
Config::write('db.password', "");

?>
