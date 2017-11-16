<?php
define('DATABASE', 'ssk98');
define('USERNAME', 'ssk98');
define('PASSWORD', 'shaffer58');
define('CONNECTION', 'sql2.njit.edu');
class dbConnection
{
protected static $connection;
private function __construct()
{
try 
{
self::$connection = new PDO('mysql:host=' . CONNECTION . ';dbname=' . DATABASE, USERNAME, PASSWORD);
self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
echo 'Connected successfully <br>';
} 
catch (PDOException $e) 
{
echo 'Connection failed ' . $e->getMessage() . '<br>';
}
}
public static function getConnection()
{
if (!self::$connection)
{
new dbConnection();
}
return self::$connection;
}
}
