<?php

/**
 * A class file to connect to database
 */
class DB_CONNECT
{

    public function __construct()
    {
        $this->connect();
    }

    public function __destruct()
    {
        $this->close();
    }

    function connect()
    {
        // import database connection variables
        require_once __DIR__ . '/db_config.php';
        // Connecting to mysql database
        $connection_cursor = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error());
        // returing connection cursor
        return $connection_cursor;
    }

    function close()
    {
        mysqli_close($this->connect());
    }

}

?>