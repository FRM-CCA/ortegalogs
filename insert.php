HTML STATIC
<?php
$conn = null;
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "dbtrace";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully<br>";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
	$conn = null;
}

//TODO A VIRER

if($conn != null){
	//ACTION QUERY INSERT/UPDATE/DELETE
	$ip="55.55.555.555555555";
	try {
		$sql = "INSERT INTO `trace`
				(`Ip`,		`Host`,		`DateCnx`,		`UserId`,		`PageId`)
				VALUES ('$ip', 'test.host', '2023-03-16 15:17:25', null, 2);";
		// use exec() because no results are returned
		$conn->exec($sql);
		$last_id = "";
		$last_id = $conn->lastInsertId(); //INSERT
		echo "New record created successfully --> lastid=" . $last_id;
	} catch(PDOException $e) {
		echo $sql . "<br>" . $e->getMessage();
	}
echo "<br><br>";
try {
	$sql = "delete from `trace` where id=35";
	// use exec() because no results are returned
	$res = $conn->exec($sql);
	echo "delete record successfully --> res=" . $res;
} catch(PDOException $e) {
	echo $sql . "<br>" . $e->getMessage();
}
echo "<br><br>";
	//SELECTION QUERY (SELECT)
	try {
		$sql =  "select Id as iddddd, Ip, Host, DateCnx, UserId, PageId from trace";
		foreach  ($conn->query($sql) as $row) {
				print $row['iddddd'] . "\t";
				print $row['Ip'] . "\t";
				print $row['Host'] . "\t";
				print $row['DateCnx'] . "<BR><BR>";
			}
	} catch(PDOException $e) {
		echo $sql . "<br>" . $e->getMessage();
	}
}

$conn = null; //attention librer cnx, sur sur autre base ex: mysqli_close($conn);
?>
HTML STATIC