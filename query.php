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
require_once "inc/db.php";

$bAffich=false;
$nameId= $pageId= $filter= $ip= $host= $date= $year= $month= $week= "";
$yearm= $m= $yearw= $w= "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (!empty($_REQUEST["nameid"])) {
		$nameId = trim($_REQUEST["nameid"]);
		//var_dump($nameId);
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
		$date = trim($_REQUEST["date"]);	//string(10) "2023-02-12"
	}
	if (!empty($_REQUEST["month"])) {
		$month = trim($_REQUEST["month"]); //string(7) "2023-02"
		$split = explode("-", $month);	//découpe une chaine via un char
		$yearm=$split[0];
		$m=$split[1];
	}
	if (!empty($_REQUEST["week"])) {
		$week = trim($_REQUEST["week"]);	//string(8) "2023-W06"
		$split = explode("W", $week);	//découpe une chaine via un char
		$yearw=$split[0];
		$w=$split[1];
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
						<label for="month">Mois</label>
						<input type="month" name="month" id="month" value="<?=$month?>">
						<label for="week">Semaine</label>
						<input type="week" name="week" id="week" value="<?=$week?>">
					<hr>
					<input type="submit" value="Submit" />
					<input type="reset" value="Reset" />
					<button id="ExcludeIP"><a class="link" href="excludeIP.php">Exclude IP</a></button>
					<button id="ClearForm">Clear</button>
				</section>
			</fieldset>
		</form>
		<hr>
			<h4><?= $query?></h4>
		<hr>
		<table>
			<tr>
				<td colspan="2">Pour un étudiant donné combien de connexions, au total</td>
			</tr>
			<tr>
<?php
if(empty($nameId) || (int) ($nameId) < 0){
	echo "<td colspan='2'></td>";
}
else{
	try {
			$dbh = new PDO($dbCnx, $user, $pass,
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$query = "SELECT count(*) as cnt from trace where UserId=$nameId";
			
			foreach ($dbh->query($query) as $row) {
					//print_r($row);
					echo "<td>".$row["cnt"]."</td>";
			}
			if($bAffich) echo "<td>".$query."</td>";
			$dbh = null;
	} catch (PDOException $e) {
			print "Erreur !: " . $e->getMessage() . "<br/>";
			die();
	}
}
?>
			</tr>

			<tr>
				<td colspan="2">Pour un étudiant donné combien de connexions, pour un jour.</td>
			</tr>
			<tr>
<?php
if(empty($nameId) || (int) ($nameId) < 0 || empty($date)){
	echo "<td colspan='2'></td>";
}
elseif((empty($nameId) || (int) ($nameId) < 0) && empty($date)){
	echo "<td colspan='2'></td>";
}else{
	try {
			$dbh = new PDO($dbCnx, $user, $pass,
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$query = "SELECT count(*) as cnt from trace where UserId=$nameId and date(datecnx) = '$date'";
			
			foreach ($dbh->query($query) as $row) {
					//print_r($row);
					echo "<td>".$row["cnt"]."</td>";
			}
			if($bAffich) echo "<td>".$query."</td>";
			$dbh = null;
	} catch (PDOException $e) {
			print "Erreur !: " . $e->getMessage() . "<br/>";
			die();
	}
}
?>
			</tr>

			<tr>
				<td colspan="2">Pour un étudiant donné combien de connexions, pour une semaine.</td>
			</tr>
			<tr>
<?php
if(empty($nameId) || (int) ($nameId) < 0 || empty($date)){
	echo "<td colspan='2'></td>";
}
elseif((empty($nameId) || (int) ($nameId) < 0) && empty($date)){
	echo "<td colspan='2'></td>";
}else{
	try {
			$dbh = new PDO($dbCnx, $user, $pass,
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$query = "SELECT count(*) as cnt, week(datecnx,1) as dt from trace where UserId=$nameId and week(datecnx,1) = week('$date',1)";
			
			foreach ($dbh->query($query) as $row) {
					//print_r($row);
					echo "<td>".$row["cnt"]." (Semaine:". $row["dt"] . ")</td>";
			}
			if($bAffich) echo "<td>".$query."</td>";
			$dbh = null;
	} catch (PDOException $e) {
			print "Erreur !: " . $e->getMessage() . "<br/>";
			die();
	}
}
?>
			</tr>

			<tr>
				<td colspan="2">Pour un étudiant donné combien de connexions pour chaque jour d'une semaine donnée =><?=$w ?></td>
			</tr>
			<tr>
<?php
if(empty($nameId) || (int) ($nameId) < 0 || empty($week)){
	echo "<td colspan='2'></td>";
}
elseif((empty($nameId) || (int) ($nameId) < 0) && empty($week)){
	echo "<td colspan='2'></td>";
}else{
	try {
			$dbh = new PDO($dbCnx, $user, $pass,
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$query = "SELECT count(*) as cnt, date(datecnx) as dt from trace 
				where UserId=$nameId and week(datecnx,1) = $w 
				group by date(datecnx) order by datecnx";
			echo "<td><table>";
			foreach ($dbh->query($query) as $row) {
					//print_r($row);
					echo "<tr><td>".$row["cnt"]."-Date:".$row["dt"]."</td></tr>";
			}
			echo "</table></td>";
			if($bAffich) echo "<td>".$query."</td>";
			$dbh = null;
	} catch (PDOException $e) {
			print "Erreur !: " . $e->getMessage() . "<br/>";
			die();
	}
}
?>
			</tr>

			<tr>
				<td colspan="2">Pour un étudiant donné combien de connexions pour chaque jour d'un mois donné =><?=$m ?></td>
			</tr>
			<tr>
<?php
if(empty($nameId) || (int) ($nameId) < 0 || empty($month)){
	echo "<td colspan='2'></td>";
}
elseif((empty($nameId) || (int) ($nameId) < 0) && empty($month)){
	echo "<td colspan='2'></td>";
}else{
	try {
			$dbh = new PDO($dbCnx, $user, $pass,
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$query = "SELECT count(*) as cnt, date(datecnx) as dt from trace 
				where UserId=$nameId and month(datecnx) = $m 
				group by date(datecnx) order by datecnx";
			echo "<td><table>";
			foreach ($dbh->query($query) as $row) {
					//print_r($row);
					echo "<tr><td>".$row["cnt"]."-Date:".$row["dt"]."</td></tr>";
			}
			echo "</table></td>";
			if($bAffich) echo "<td>".$query."</td>";
			$dbh = null;
	} catch (PDOException $e) {
			print "Erreur !: " . $e->getMessage() . "<br/>";
			die();
	}
}
?>
			</tr>

			<tr>
				<td colspan="2">Fournir un classement des étudiants en fonction du nombre de connexions totales</td>
			</tr>
			<tr>
<?php
	try {
			$dbh = new PDO($dbCnx, $user, $pass,
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$query = "SELECT count(*) as cnt, user.Name 
				from trace inner join user on trace.UserId=user.Id
				group by userid 
				order by count(*) desc";
			echo "<td><table>";
			foreach ($dbh->query($query) as $row) {
					//print_r($row);
					echo "<tr><td>".$row["cnt"]."-User:".$row["Name"]."</td></tr>";
			}
			echo "</table></td>";
			if($bAffich) echo "<td>".$query."</td>";
			$dbh = null;
	} catch (PDOException $e) {
			print "Erreur !: " . $e->getMessage() . "<br/>";
			die();
	}
?>
			</tr>

			<tr>
				<td colspan="2">Fournir un classement des étudiants en fonction du nombre de connexions par mois</td>
			</tr>
			<tr>
<?php
	try {
			$dbh = new PDO($dbCnx, $user, $pass,
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$query = "SELECT count(*) as cnt, month(dateCnx) as dt, user.Name from trace 
				inner join user on trace.UserId=user.Id
				group by userid, month(dateCnx)
				order by count(*) desc, month(dateCnx) desc";
			echo "<td><table>";
			foreach ($dbh->query($query) as $row) {
					//print_r($row);
					echo "<tr><td>".$row["cnt"]."-Date=".$row["dt"]."-User:".$row["Name"]."</td></tr>";
			}
			echo "</table></td>";
			if($bAffich) echo "<td>".$query."</td>";
			$dbh = null;
	} catch (PDOException $e) {
			print "Erreur !: " . $e->getMessage() . "<br/>";
			die();
	}
?>
			</tr>

			<tr>
				<td colspan="2">Fournir un classement des étudiants en fonction du nombre de connexions par semaine</td>
			</tr>
			<tr>
<?php
	try {
			$dbh = new PDO($dbCnx, $user, $pass,
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$query = "SELECT count(*) as cnt, week(dateCnx,1) as dt, user.Name from trace 
				inner join user on trace.UserId=user.Id
				group by userid, week(dateCnx,1)
				order by count(*) desc, week(dateCnx,1) desc";
			echo "<td><table>";
			foreach ($dbh->query($query) as $row) {
					//print_r($row);
					echo "<tr><td>".$row["cnt"]."-Date=".$row["dt"]."-User:".$row["Name"]."</td></tr>";
			}
			echo "</table></td>";
			if($bAffich) echo "<td>".$query."</td>";
			$dbh = null;
	} catch (PDOException $e) {
			print "Erreur !: " . $e->getMessage() . "<br/>";
			die();
	}
?>
			</tr>

			<tr>
				<td colspan="2">Fournir un classement des étudiants en fonction du nombre de connexions par jour</td>
			</tr>
			<tr>
<?php
	try {
			$dbh = new PDO($dbCnx, $user, $pass,
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$query = "SELECT count(*) as cnt, date(dateCnx) as dt, user.Name from trace 
				inner join user on trace.UserId=user.Id
				group by userid, date(dateCnx)
				order by count(*) desc, date(dateCnx) desc";
			echo "<td><table>";
			foreach ($dbh->query($query) as $row) {
					//print_r($row);
					echo "<tr><td>".$row["cnt"]."-Date=".$row["dt"]."-User:".$row["Name"]."</td></tr>";
			}
			echo "</table></td>";
			if($bAffich) echo "<td>".$query."</td>";
			$dbh = null;
	} catch (PDOException $e) {
			print "Erreur !: " . $e->getMessage() . "<br/>";
			die();
	}
?>
			</tr>

			<tr>
				<td colspan="2">Pour un étudiant donné, lister les pages vues dans une semaine, =><?=$w ?></td>
			</tr>
			<tr>
<?php
if(empty($nameId) || (int) ($nameId) < 0 || empty($w)){
	echo "<td colspan='2'></td>";
}
elseif((empty($nameId) || (int) ($nameId) < 0) && empty($w)){
	echo "<td colspan='2'></td>";
}else{
	try {
			$dbh = new PDO($dbCnx, $user, $pass,
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$query = "SELECT distinct page.Page from trace 
				inner join page on page.Id=PageId 
				where UserId=$nameId and week(datecnx,1) = $w 
				order by datecnx";
			echo "<td><table>";
			foreach ($dbh->query($query) as $row) {
					//print_r($row);
					echo "<tr><td>".$row["Page"].""."</td></tr>";
			}
			echo "</table></td>";
			if($bAffich) echo "<td>".$query."</td>";
			$dbh = null;
	} catch (PDOException $e) {
			print "Erreur !: " . $e->getMessage() . "<br/>";
			die();
	}
}
?>
			</tr>

			<tr>
				<td colspan="2">Pour un étudiant donné, lister les pages vues dans un mois, =><?=$m ?></td>
			</tr>
			<tr>
<?php
if(empty($nameId) || (int) ($nameId) < 0 || empty($m)){
	echo "<td colspan='2'></td>";
}
elseif((empty($nameId) || (int) ($nameId) < 0) && empty($m)){
	echo "<td colspan='2'></td>";
}else{
	try {
			$dbh = new PDO($dbCnx, $user, $pass,
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$query = "SELECT distinct page.Page from trace 
				inner join page on page.Id=PageId 
				where UserId=$nameId and month(datecnx) = $m 
				order by datecnx";
			echo "<td><table>";
			foreach ($dbh->query($query) as $row) {
					//print_r($row);
					echo "<tr><td>".$row["Page"].""."</td></tr>";
			}
			echo "</table></td>";
			if($bAffich) echo "<td>".$query."</td>";
			$dbh = null;
	} catch (PDOException $e) {
			print "Erreur !: " . $e->getMessage() . "<br/>";
			die();
	}
}
?>
		</tr>

		<tr>
				<td colspan="2">Pour une page donnée, afficher la liste des étudiants ayant consulté la page</td>
			</tr>
			<tr>
<?php
if(empty($pageId) || (int) ($pageId) < 0){
	echo "<td colspan='2'></td>";
}
else{
	try {
			$dbh = new PDO($dbCnx, $user, $pass,
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$query = "SELECT distinct name, page FROM `trace` 
			inner join user on user.id = UserId 
			inner join page on page.id = PageId 
			where pageid=$pageId";
			echo "<td><table>";
			foreach ($dbh->query($query) as $row) {
					//print_r($row);
					echo "<tr><td>".$row["name"].""."</td></tr>";
			}
			echo "</table></td>";
			if($bAffich) echo "<td>".$query."</td>";
			$dbh = null;
	} catch (PDOException $e) {
			print "Erreur !: " . $e->getMessage() . "<br/>";
			die();
	}
}
?>
		</tr>
		
		<tr>
				<td colspan="2">Pour une page donnée, afficher la liste des étudiants ayant consulté la page le mois =><?=$m ?></td>
			</tr>
			<tr>
<?php
if(empty($pageId) || (int) ($pageId) < 0 || empty($m)){
	echo "<td colspan='2'></td>";
}
elseif((empty($pageId) || (int) ($pageId) < 0) && empty($m)){
	echo "<td colspan='2'></td>";
}else{
	try {
			$dbh = new PDO($dbCnx, $user, $pass,
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$query = "SELECT distinct name, page FROM `trace` 
			inner join user on user.id = UserId 
			inner join page on page.id = PageId 
			where pageid=$pageId and month(datecnx) = $m";
			echo "<td><table>";
			foreach ($dbh->query($query) as $row) {
					//print_r($row);
					echo "<tr><td>".$row["name"].""."</td></tr>";
			}
			echo "</table></td>";
			if($bAffich) echo "<td>".$query."</td>";
			$dbh = null;
	} catch (PDOException $e) {
			print "Erreur !: " . $e->getMessage() . "<br/>";
			die();
	}
}
?>
		</tr>
		<tr>
				<td colspan="2">Pour un utilisateur, donner la liste des adresses I.P. en enlevant la/les adresse(s) IP voulues.</td>
			</tr>
			<tr>
<?php
if(empty($nameId) || (int) ($nameId) < 0){
	echo "<td colspan='2'></td>";
}
else{
	try {
			$dbh = new PDO($dbCnx, $user, $pass,
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$query = "SELECT distinct IP from trace 
				inner join page on page.Id=PageId 
				where UserId=$nameId and ip not in (select ip from excludeip) 
				order by IP";
			echo "<td><table>";
			foreach ($dbh->query($query) as $row) {
					//print_r($row);
					echo "<tr><td>".$row["IP"].""."</td></tr>";
			}
			echo "</table></td>";
			if($bAffich) echo "<td>".$query."</td>";
			$dbh = null;
	} catch (PDOException $e) {
			print "Erreur !: " . $e->getMessage() . "<br/>";
			die();
	}
}
?>
		</tr>

		</table>
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
				selectDate = document.querySelectorAll('input[type=week]');
				for (let i = 0; i < selectDate.length; i++) {
					selectDate[i].value = "";
				}
				selectDate = document.querySelectorAll('input[type=month]');
				for (let i = 0; i < selectDate.length; i++) {
					selectDate[i].value = "";
				}
				document.forms[0].submit();
			}
		}
	</script>
</body>
</html>