<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
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
abstract class collection 
{
protected $html;
static public function newdb() 
{
$model = new static::$modelName;
return $model;
}
static public function findAll()
{
$db = dbConnection::getConnection();
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
$db = dbConnection::getConnection();
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
$db = dbConnection::getConnection();
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


class model 
{
protected $tableName;
protected static $statement;
public function save()
{
$array = get_object_vars($this);
unset($array["tableName"]);
if ($this->id == '') 
{
echo "Insert Record </br>";
$sql = $this->insert();
}
else 
{
$sql = $this->update();
}
$db = dbConnection::getConnection();
self::$statement = $db->prepare($sql);
foreach ($array as $key=>$value)
{
if ($this->id == '')
{
self::$statement->bindValue(":$key","$value");
}
else {
if ($value != '' && $key != "id")
{
self::$statement->bindValue(":$key","$value");
}
}
}
self::$statement->execute();
$lastId = $db->lastInsertId();
return ($lastId);
}
public function insert()
{
$array = get_object_vars($this);
unset($array["tableName"]);
$columnString = implode(',', array_keys($array));
$valueString = ":".implode(',:', array_keys($array));
$sql = "INSERT INTO $this->tableName (" . $columnString . ") VALUES (" . $valueString . ")";
print_r($sql);
return $sql;		
}	
public function update()
{
$array = get_object_vars($this);
unset($array["tableName"]);
$sql = "UPDATE ". $this->tableName ." SET";
foreach ($array as $key => $value){
if ($value != "" & $key != "id")
{
$sql.= " " .$key ." = :$key ,";
//$values[":$key"] = $value;
}
} 
$sql = substr($sql,0,-1);
$sql.= " WHERE id = " .$this->id;
echo $sql;
return $sql; 
}
}









$records = accounts::findAll();
print_r($records);
$records = todos::findAll();
print_r($records);
$record = todos::findOne(2);
print_r($record);
$records = todos::deleteRecord(2);
print_r($record);



?>
