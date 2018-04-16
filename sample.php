<?php
   $wordtype=[];
$wordcol=[];
$txt=array();
    $stdin = fopen('php://stdin', 'r');
$servername = "localhost";
$username = "root";
$password = "";
$i=0;
    while (($line = fgets($stdin)) !== FALSE) {
         $word_arr = explode(" ", $line); 
        foreach($word_arr as $word){
		$txt[$i]=$word;
		$i++;
		}
		
    }
$uniq=array_unique($txt);
$keys=array_keys($uniq);
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "CREATE DATABASE textdb";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully\n";
} else {
    echo "Error creating database: " . $conn->error;
}
mysqli_select_db($conn, "textdb");
$c=count($uniq);

for($a=0;$a<$c;$a++)
{
$wordtype[$a]="varchar"; 
$wordcol[$a]="data".$a;
}
$query = "CREATE TABLE words ( ";
for($i=0; $i<count($uniq) ;$i=$i+1)
{
	if($i!=$c-1)
  $query .= $wordcol[$i] . " " . $wordtype[$i] . "(10)"."," ;
else
	$query .= $wordcol[$i] . " " . $wordtype[$i] . "(10)";
}
$query .= " ); ";

if($conn->query($query))
	echo "Table created successfully\n";
else {
    echo "Error creating table: " . $conn->error;
}
$insdata="insert into words values ( ";
for($i=0; $i<10 ;$i=$i+1)
{
	$r=$uniq[$keys[$i]];
		if($i!=$c-1)
$insdata.=$r . "". ',';
else
	$insdata.=$r;
}
$insdata .= ");" ;
if($conn->query($insdata))
	echo "Data added successfully\n";
else {
    echo "Error insertion: " . $conn->error;
}
$tabledata="select count(*) from words ";
$result = $conn->query($tabledata);
$count = $result->num_rows;
echo "Number of distinct words:".$count;

$query="create table watchlist(word varchar(10))";
if($conn->query($query))
	echo "watchlist table created successfully\n";
else {
    echo "Error creating table: " . $conn->error;
}
$insertword="insert into watchlist values('the','template','water','organic')";
if($conn->query($insertword))
	echo "Data added in watchlist\n";
else {
    echo "Error creating table: " . $conn->error;
}
$output = 'output.txt';
$handle = fopen($output, 'a') or die('Cannot open file:  '.$output);

for($i=0;$i<$c;$i++)
{
$findword="SELECT word FROM watchlist WHERE word IN (SELECT $wordcol[$i]  FROM words)";
$result = $conn->query($findword);
if($result)
{
	fwrite($handle, $result['word']);
	}
}	
fclose($handle);
$handle = fopen($output, 'r');
$data = fread($handle,filesize($output));
echo $data;
$conn->close();
	?>