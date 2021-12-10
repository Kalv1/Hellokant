<?php

namespace Hellokant\Factory;

use PDO;

class ConnectionFactory
{
    public static $instance;

    public static function makeConnection(array $conf)
    {
        $hostndbname = $conf['driver'] . ":host=" . $conf['host'] . ";dbname=" . $conf['database'];
        self::$instance = new PDO($hostndbname, $conf['username'], $conf['password'], array(PDO::ATTR_PERSISTENT => TRUE,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        return self::$instance;
    }

    public static function getConnection()
    {
        return self::$instance;
    }

}