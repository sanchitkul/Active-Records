<?php
define('DATABASE', 'ssk98');
define('USERNAME', 'ssk98');
define('PASSWORD', 'shaffer58');
define('CONNECTION', 'sql1.njit.edu');
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
class collection {
protected $html;
static public function newdb() 
{

$model = new static::$modelName;
return $model;

}
static public function findAll()
{

$db = dbConn::getConnection();
$tableName = get_called_class();
$sql = 'SELECT * FROM ' . $tableName;
$statement = $db->prepare($sql);
$statement->execute();
$class = static::$modelName;
$statement->setFetchMode(PDO::FETCH_CLASS, $class);
$recordsSet =  $statement->fetchAll();
return $recordsSet;

}
static public function findOne($id) 
{

$db = dbConn::getConnection();
$tableName = get_called_class();
$sql = 'SELECT * FROM ' . $tableName . ' WHERE id =' . $id;
$statement = $db->prepare($sql);
$statement->execute();
$class = static::$modelName;
$statement->setFetchMode(PDO::FETCH_CLASS, $class);
$recordsSet =  $statement->fetchAll();
return $recordsSet;

}
public static function deleteRecord($id)
{

$db = dbConn::getConnection();
$tableName = get_called_class();
$sql = 'DELETE FROM '.$tableName.' WHERE id='.$id;
echo $sql;
$statement = $db->prepare($sql);
$statement->execute();
echo 'done';

}

}
class accounts extends collection 
{

protected static $modelName = 'account';

}
class todos extends collection 
{

protected static $modelName = 'todo';

}













?>