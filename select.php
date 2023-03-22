HTML STATIC
<?php
echo "<h1>Titre ". (10*12) ."</h1>";
$conn = null;
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "dbtrace";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully<BR>";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
	$conn = null;
}

if($conn != null){
	try {
		$sql =  "select Id, Ip, Host, DateCnx, UserId, PageId from trace";
		foreach  ($conn->query($sql) as $row) {
				print $row['Id'] . "\t";
				print $row['Ip'] . "\t";
				print $row['Host'] . "\t";
				print $row['UserId'] . "\t";
				print $row['DateCnx'] . "<BR><BR>";
		}
	} catch(PDOException $e) {
		echo $sql . "<br>" . $e->getMessage();
	}
}

$conn = null; //attention librer cnx, sur sur autre base ex: mysqli_close($conn);
?>
HTML STATIC
fin