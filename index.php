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
echo ' <br>';
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
echo ('<h1>SELECT all records of Accounts Table</h1></br>');
echo self::$sql;
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
echo ('<h1>SELECT all records of Accounts Table</h1></br>');
}
static public function deleteRecord($id)
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
//echo "Insert Record </br>";
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
//print_r($sql);
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
//echo $sql;
return $sql; 
}
public function delete()
{
$array = get_object_vars($this);
$sql = "DELETE FROM " . $this->tableName . " WHERE id = " . $this->id;
$db = dbConnection::getConnection();
self::$statement = $db->prepare($sql);
self::$statement->execute();
}
}
class account extends model 
{
public $id;
public $email;
public $fname;
public $lname;
public $phone;
public $birthday;
public $gender;
public $password;
public function __construct()
{
 $this->tableName = 'accounts';
}
}
class todo extends model 
{
public $id;
public $owneremail;
public $ownerid;
public $createdate;
public $duedate;
public $message;
public $isdone;
public function __construct()
{
 $this->tableName = 'todos';
}
}


class tableClass
{
private static $table;
public static function checkRecord ($rec)
{
if(count($rec) == 0)
{
$printtb = "Records not found</br>";
}
else 
{
$printtb = self::printTable($rec);
}
return $printtb;
}
public static function printTable($rec)
{
self::$table.= '<table>';
self::$table.= '<tr>';
$headerFields = $rec[0];
foreach ($headerFields as $key => $value) 
{
self::$table .= "<th>$key</th>";
}
self::$table.= '</tr>';
foreach ($rec as $record)
{
self::$table.= '<tr>';
foreach($record as $key => $value)
{
 self::$table.= "<td>$value</td>";
}
self::$table .= '</tr>';
}
self::$table .= '</table> <br>';
return self::$table;
}
}
echo '<h3>1.Select all records of Accounts Table</h3></br>';
echo '<h3>2.Select all records of todos Table</h3></br>';
echo '<h3>3.Select one record from accounts table</h3></br>';
echo '<h3>4.Select one record from todos table</h3></br>';
echo "<h3>5.Inserted record into Accounts table</h3> </br> ";
echo "<h3>6.Updated record into Accounts table </h3> </br> ";
echo "<h3>7.Insert and Updated record into todos table </h3> </br> ";
echo "<h3>8.Id=2 from todos deleted </h3></br>";
echo "<h3>9. Id=4 from accounts deleted </h3></br>";
$record = accounts::findAll();
$record = tableClass::checkRecord($record);
$record = todos::findAll();
$record = tableClass::checkRecord($record);
$record = accounts::findOne(10);
$record = tableClass::checkRecord($record);
$record = todos::findOne(3);
$record = tableClass::checkRecord($record);
$record = ($record);
$newRecord = new account();
$newRecord->email="xyz@gmail.com";
$newRecord->fname="Shah Rukh";
$newRecord->lname="Khan";
$newRecord->phone='555';
$newRecord->birthday='07231992';
$newRecord->gender='Male';
$newRecord->password='123';
$new = $newRecord->save();

$updateRecord = new account();
$updateRecord->id = $new;
$updateRecord->email="srk@gmail.com";
$updateId = $updateRecord->save();
echo ($record);

$newRecord = new todos();
$newRecord->email="abc@gmail.com";
$newRecord->fname="Hritik";
$newRecord->lname="Roshan";
$newRecord->phone='555';
$newRecord->birthday='07221992';
$newRecord->gender='Male';
$newRecord->password='000';
/*$new = $newRecord->save();

$updateRecord = new account();
$updateRecord->id = $new;
$updateRecord->email="Hritik@gmail.com";
$updateId = $updateRecord->save();
*/

$record= new todo();
$record->id=2;
$record->delete();
$records = todos::findAll();


$record = new account();
$record->id=4;
$record->delete();
$record = todos::findAll();
//$table = tableClass::printTable;

//echo ($record);

















?>
