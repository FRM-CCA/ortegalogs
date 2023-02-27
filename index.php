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
	<h1>TP Ortega Users</h1>
		<table>
			<tr>
				<th>Id</th>
				<th>Ip</th>
				<th>Host</th>
				<th>Date Cnx</th>
				<th>User Id</th>
				<th>Page Id</th>
				<th>Name/Login</th>
				<th>Page Name</th>
			</tr>
<?php
$dbCnx = "mysql:host=localhost;dbname=DbTrace";
$user = "root"; //pour mon uwamp/xammp
$pass = "root"; //pour xamp mettre vide ""

$nameId = $pageId = $filter= $ip= $host = $date = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (!empty($_REQUEST["nameid"])) {
		$nameId = trim($_REQUEST["nameid"]);
	}
	if (!empty($_REQUEST["pageid"])) {
		$pageId = trim($_REQUEST["pageid"]);
	}
	if (!empty($_REQUEST["ip"])) {
		$ip = trim($_REQUEST["ip"]);
	}
	if (!empty($_REQUEST["host"])) {
		$host = trim($_REQUEST["host"]);
	}
	if (!empty($_REQUEST["date"])) {
		$date = trim($_REQUEST["date"]);
		//var_dump($date);
	}
}

try {
    $dbh = new PDO($dbCnx, $user, $pass,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $query = "SELECT t.*, u.Name, p.Page FROM trace as t
				inner join page as p on p.Id = t.PageId
				left join user as u on u.Id = t.UserId";
		// Gestion des filtres
    if (!empty($nameId) && (int) ($nameId) > 0) {
      $filter .= " where u.id =" . $nameId;
    }
		if (!empty($pageId) && (int) ($pageId) > 0) {
			if(empty($filter))
      	$filter .= " where p.id =" . $pageId;
			else
				$filter .= " and p.id =" . $pageId;
    }
		if (!empty($ip) && (int) ($ip) > 0) {
			if(empty($filter))
      	$filter .= " where t.ip = '$ip'";
			else
				$filter .= " and t.ip = '$ip'";
    }
		if (!empty($date)) {
			$date=htmlspecialchars($date);
			if(empty($filter))
				$filter .= " where t.datecnx BETWEEN '$date 00:00:00' and '$date 23:59:59'" ;
      	//$filter .= " where date(t.datecnx) = '$date'";
			else
				$filter .= " and t.datecnx BETWEEN '$date 00:00:00' and '$date 23:59:59'";
				//$filter .= " and date(t.datecnx) = '$date'";
    }
		if (!empty($host)) {
			$host=htmlspecialchars($host);
			if(empty($filter))
      	$filter .= " where t.host like '%$host%'";
			else
				$filter .= " and t.host like '%$host%'";
    }
		$query.=$filter; //idem=> $query= $query . $filter;

    foreach ($dbh->query($query) as $row) {
        //print_r($row);
        echo ("<tr>");
        echo ("<td>" . $row["Id"] . "</td>");
        echo ("<td>" . $row["Ip"] . "</td>");
        echo ("<td>" . $row["Host"] . "</td>");
        echo ("<td>" . $row["DateCnx"] . "</td>");
        echo ("<td>" . $row["UserId"] . "</td>");
        echo ("<td>" . $row["PageId"] . "</td>");
        echo ("<td>" . $row["Name"] . "</td>");
        echo ("<td>" . $row["Page"] . "</td>");
        echo ("</tr>");
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
?>
		</table>
		<form action="" method="POST">
			<fieldset>
			<legend>Filtres</legend>
				<section>
					<article>
						<label for="nameid">Nom</label>
						<select name="nameid" id="nameid">
							<option value="-1">(All)</option>
							<?php
try {
    $dbh = new PDO($dbCnx, $user, $pass,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    foreach ($dbh->query('SELECT id, Name from user order by Name') as $row) {
        //print_r($row);
        $selected = "";
        if (!empty($nameId)) {
            if ($nameId == $row["id"]) {
                $selected = "selected";
            }

        }
        echo ('<option ' . $selected . ' value="' . $row["id"] . '">' . $row["Name"] . '</option>');
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
?>
						</select>
					</article>
					<article>
						<label for="pageid">Page</label>
						<select name="pageid" id="pageid">
							<option value="-1">(All)</option>
							<?php
try {
    $dbh = new PDO($dbCnx, $user, $pass,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    foreach ($dbh->query('SELECT id, Page from Page order by Page') as $row) {
        //print_r($row);
        $selected = "";
        if (!empty($pageId)) {
            if ($pageId == $row["id"]) {
                $selected = "selected";
            }
        }
        echo ('<option ' . $selected . ' value="' . $row["id"] . '">' . $row["Page"] . '</option>');
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
?>
						</select>
					</article>
					<article>
						<label for="ip">IP</label>
						<select name="ip" id="ip">
							<option value="-1">(All)</option>
							<?php
try {
    $dbh = new PDO($dbCnx, $user, $pass,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    foreach ($dbh->query('SELECT distinct ip from trace order by ip') as $row) {
        //print_r($row);
        $selected = "";
        if (!empty($ip)) {
            if ($ip == $row["ip"]) {
              $selected = "selected";
            }
        }
        echo ('<option ' . $selected . ' value="' . $row["ip"] . '">' . $row["ip"] . '</option>');
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
?>
						</select>
					</article>
					<article>
						<label for="date">Date</label>
						<input type="date" name="date" id="date" value="<?=$date?>">
					</article>
					<article>
						<label for="host">Host</label>
						<input type="text" name="host" id="host" value="<?=$host?>">
					</article>
					<hr>
					<input type="submit" value="Submit" />
					<input type="reset" value="Reset" />
					<button id="ExcludeIP"><a class="link" href="excludeIP.php">Exclude IP</a></button>
					<button id="ClearForm">Clear</button>
				</section>
			</fieldset>
		</form>
		<h4><?= $query?></h4>
	<script>
		window.onload = function () {
			let btnClear = document.getElementById("ClearForm");
			btnClear.onclick = function () {
				let selectTags = document.getElementsByTagName("select");
				for (let i = 0; i < selectTags.length; i++) {
					selectTags[i].selectedIndex = 0;
				}
				let selectText = document.querySelectorAll('input[type=text]');
				for (let i = 0; i < selectText.length; i++) {
					selectText[i].value = "";
				}
				let selectDate = document.querySelectorAll('input[type=date]');
				for (let i = 0; i < selectDate.length; i++) {
					selectDate[i].value = "";
				}
				document.forms[0].submit();
			}
		}
	</script>
</body>
</html>