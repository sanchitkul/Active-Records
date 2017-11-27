<?

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
