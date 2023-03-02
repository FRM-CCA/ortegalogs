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
<?php
$nameId = $ip = $method = $action= $retval="";
    //RECUP PARAM=ACTION VIA GET OU POST
    if(isset($_REQUEST["param"]) && !empty($_REQUEST["param"])){
        $action = strtolower(trim($_REQUEST["param"]));
    }
    if(isset($_REQUEST["retval"]) && !empty($_REQUEST["retval"])){
        $retval = strtolower(trim($_REQUEST["retval"]));
    }
    switch ($action) {
        case 'deleteip':
            echo "<h2>Vous venez d'effacer $retval IP</h2>";
            break;
        case 'createip':
            echo "<h2>Vous venez d'ajouter $retval IP</h2>";
            break;  
        default:
            # code...
            break;
    }
?>
		<table class="tdcenter">
			<tr>
				<th>Id</th>
				<th>Ip</th>
                <th>Delete<button id="ExcludeIP"><a class="link" href="excludeIPCRUD.php?param=create">Add IP</a></button></th>
			</tr>
<?php
require_once "inc/db.php";

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
        echo ("<td>" . $row["id"] . "</a></td>");
        echo ("<td>" . $row["ip"] . "</a></td>");
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
        <button><a class="link" href="index.php">Goto to main Page</a></button>
		<h4><?=$query?></h4>
</body>
</html>