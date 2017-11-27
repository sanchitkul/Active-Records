<?

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
