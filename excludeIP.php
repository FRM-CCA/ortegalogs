<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
	<title>Ortega Logs</title>
</head>
<body>
	<h1>TP Ortega Exclude IP</h1>
		<table class="tdcenter">
			<tr>
				<th>Id</th>
				<th>Ip</th>
                <th>Delete<button id="ExcludeIP"><a class="link" href="excludeIPCRUD.php?param=create">Add IP</a></button></th>
			</tr>
<?php
$dbCnx = "mysql:host=localhost;dbname=DbTrace";
$user = "root"; //pour mon uwamp/xammp
$pass = "root"; //pour xamp mettre vide ""

$nameId = $ip = $method = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_REQUEST["nameid"])) {
        $nameId = trim($_REQUEST["nameid"]);
    }
    if (!empty($_REQUEST["ip"])) {
        $ip = trim($_REQUEST["ip"]);
    }
}

try {
    $dbh = new PDO($dbCnx, $user, $pass,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $query = "SELECT id, ip FROM excludeip order by ip";

    foreach ($dbh->query($query) as $row) {
        //print_r($row);
        echo ("<tr>");
        echo ("<td><a href='excludeIPCRUD.php?param=update&id=" . $row["id"] . "'>" . $row["id"] . "</a></td>");
        echo ("<td><a href='excludeIPCRUD.php?param=update&id=" . $row["id"] . "'>" . $row["ip"] . "</a></td>");
        echo ("<td><button class='btndelete'><a class='link' href='excludeIPCRUD.php?param=delete&id=" . $row["id"] . "'>X</a></button></td>");
        echo ("</tr>");
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
?>
		</table>
		<h4><?=$query?></h4>
</body>
</html>