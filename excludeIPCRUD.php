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
	<h1>TP Ortega Exclude IP CRUD</h1>
<?php
$dbCnx = "mysql:host=localhost;dbname=DbTrace";
$user = "root"; //pour mon uwamp/xammp
$pass = "root"; //pour xamp mettre vide ""

$id = $ip = $iddb = $ipdb = "";
$method= $action = $query="";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $method="POST";
    if (!empty($_POST["id"])) {
        $id = trim($_POST["id"]);
    }
    if (!empty($_POST["ip"])) {
        $ip = trim($_POST["ip"]);
    }
    if (!empty($_POST["param"])) {
        $action = strtolower(trim($_POST["param"]));
    }
} else {
    $method="GET";
    if (!empty($_GET["id"])) {
        $id = trim($_GET["id"]);
    }
    if(isset($_GET["param"]) && !empty($_GET["param"])){
        $action = strtolower(trim($_GET["param"]));
    }
}

if(isset($_REQUEST["param"]) && !empty($_REQUEST["param"])){
    $action = strtolower(trim($_REQUEST["param"]));
}
switch ($action) {
    case 'create':
        break;
    case 'update':
        break;
    case 'delete':
        break;
    default:
        $action="read";   //read
        break;
}

// if (empty($id) || (int) $id < 0) {
//     die("No Parameter...");
// }
switch ($action) {
    case 'create':
        if($method=="GET"){
        ?>
        <table class="tdcenter">
        <tr>
            <th>Ip</th>
            <th>Choix</th>
        </tr>
        <?php
        try {
            $dbh = new PDO($dbCnx, $user, $pass,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $query = "select distinct ip FROM trace where ip not in (select ip from excludeip)";
            // order by ip";
            foreach ($dbh->query($query) as $row) {
                echo("<tr>");
                echo("<td>".$row["ip"]."</td>");
                echo("<td>");
                echo("<form action='' method='POST'>");
                echo("<input type='hidden' name='param' value='".$action."'>");
                echo("<input type='hidden' name='ip' value='".$row["ip"]."'>");
                echo("<input type='submit' value='Add IP'>");
                echo("</form>");
                echo("</td>");
                echo("</tr>");
            }
            $dbh = null;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
        ?>
        </table>
        <?php
        }else{
            $dbh = new PDO($dbCnx, $user, $pass,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                //TODO 
            $query = "insert into distinct ip FROM trace where ip not in (select ip from excludeip)";
        }
        break;
    case "r":
    case "u":
    case "d":
        ?>
        <form action="" method="POST">
		<fieldset>
			<legend>Exclude IP</legend>
        <?php
        try {
            $dbh = new PDO($dbCnx, $user, $pass,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $query = "SELECT id, ip FROM excludeip where id=$id order by ip";
            foreach ($dbh->query($query) as $row) {
                $iddb = $row["id"];
                $ipdb = $row["ip"];
            }
            $dbh = null;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
        ?>
				<input type="text"  name="id" value="<?=$iddb?>" readonly>
				<input type="text" name="ip" value="<?=$ipdb?>">
				<input type="submit" value="Submit" />
				<input type="reset" value="Reset" />
			</section>
		</fieldset>
	</form>
    <?php
        break;
    default:
       break;
}
?>
	<h4><?=$query?></h4>
</body>
</html>