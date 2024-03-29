<?php
require_once "inc/cache_clear.php";
?>
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
	<button><a class="link" href="index.php">Version classique</a></button>
	(voir page test.Ajax.htm, peut-etre plus simple pour comprendre)
<?php
# $dbCnx = "mysql:host=localhost;dbname=DbTrace";
# $user = "root"; //pour mon uwamp/xammp
# $pass = "root"; //pour xamp mettre vide ""
# include ci-dessous pour unifier les infomations
require_once "inc/db.php";

$nameId = $nameUser = $pageId = $pageName = $filter= $ip= $host = $date = "";
$query= $orderby = $tri= $ordre="";

if (!empty($_REQUEST["tri"])) {
	$tri = trim($_REQUEST["tri"]);
}
if (!empty($_REQUEST["ordre"])) {
	$ordre = trim($_REQUEST["ordre"]);
}
//var_dump($tri, $ordre);
if(!empty($tri))
	$orderby= " order by ". $tri . " " . $ordre;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (!empty($_REQUEST["nameid"])) {
		$nameId = trim($_REQUEST["nameid"]);
	}
	if (!empty($_REQUEST["nameuser"])) {
		$nameUser = trim($_REQUEST["nameuser"]);
	}
	if (!empty($_REQUEST["pageid"])) {
		$pageId = trim($_REQUEST["pageid"]);
	}
	if (empty($_REQUEST["pagename"])) {
		if(trim($pageName)=="")	//si vide on reprend pas la vielle données
		$pageId="";
	}
	else{
		$pageName = trim($_REQUEST["pagename"]);
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
?>
		<form action="" method="POST">
			<fieldset>
			<legend>Filtres</legend>
				<section>
					<article>
						<label>Pas d'Ajax ici (on pourrait le faire)</label><br/>
						<label for="nameuser">Nom</label>
						<input type="hidden" name="nameid" id="nameid" value="<?=$nameId?>"/>
						<input list="userList" name="nameuser" id="nameuser" value="<?=$nameUser?>" />
						<datalist id="userList">
							<option label="(All Users)" data-id="-1" value="(All)">
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
        echo ('<option label=""' . $selected . ' data-id="' . $row["id"] . '" value="' . $row["Name"] . '">');
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
?>
							</datalist>
					</article>
					<script>
						document.on
					</script>
					<article>
					<label>Ici Ajax, si click sur Nom</label><br/>
						<label for="pageid">Page</label>
						<!-- <select name="pageid" id="pageid"> -->
							<!-- <option value="-1">(All)</option>		 -->
						<input type="hidden" name="pageid" id="pageid" value="<?=$pageId?>"/>
						<input list="pageList" name="pagename" id="pagename" value="<?=$pageName?>" />
							<datalist id="pageList">
								<option label="(All Pages)" data-id="-1" value="(All)">
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
        // echo ('<option ' . $selected . ' value="' . $row["id"] . '">' . $row["Page"] . '</option>');
				echo ('<option label=""' . $selected . ' data-id="' . $row["id"] . '" value="' . $row["Page"] . '">');
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
    foreach ($dbh->query('SELECT distinct ip from trace order by CAST(left(ip, POSITION("." IN ip)-1) AS SIGNED), ip;') as $row) {
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
<?php
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
// NOT IN EXCLUDEIP
if(empty($filter))
	 $filter .= " where t.ip not in (select ip from excludeip)";
 else
	 $filter .= " and t.ip not in (select ip from excludeip)";
$query.=$filter; //idem=> $query= $query . $filter;
$query.=$orderby;
//var_dump($query);
?>
		<h4><?= $query?></h4>
		<table>
			<tr>
				<th><a href="index.php?tri=Id&ordre=asc">Id</a></th>
				<th><a href="index.php?tri=Ip&ordre=asc">Ip</a></th>
				<th><a href="index.php?tri=Host&ordre=asc">Host</a></th>
				<th><a href="index.php?tri=DateCnx&ordre=asc">Date Cnx DB</a></th>
				<th><a href="index.php?tri=DateCnx&ordre=asc">Date Cnx fr</a></th>
				<th>User Id</th>
				<th>Page Id</th>
				<th>Name/Login</th>
				<th>Page Name</th>
			</tr>
	<?php
		try {
    $dbh = new PDO($dbCnx, $user, $pass,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
 
    foreach ($dbh->query($query) as $row) {
        //print_r($row);
        echo ("<tr>");
        echo ("<td>" . $row["Id"] . "</td>");
        echo ("<td>" . $row["Ip"] . "</td>");
        echo ("<td>" . $row["Host"] . "</td>");
        echo ("<td>" . $row["DateCnx"] . "</td>");
				$datetime = new DateTime($row["DateCnx"]);
				echo ("<td>" . $datetime->format('d/m/Y H:i:s') . "</td>");
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
	<script>
		async function showPages(id) {
			// lire notre JSON
			let pages;
			try {
				let response = await fetch('API/selectpage.php?userid='+id);
				pages = await response.json();
				//console.log(pages);
				//let pages = await response.body;
			} catch(err) {
				alert(err); // TypeError: failed to fetch
			}
			return pages;
		}

		//https://keyjs.dev/
		//https://fr.javascript.info/async-await
		function doKeyUp(e) {
			console.log(event.target);
			//if (e.preventDefault) { e.preventDefault(); }
			if (e.key == "ArrowLeft" || e.key == "ArrowRight" || e.key == "ArrowUp" || e.key == "ArrowDown") { return; }	//on vire les fleches
			//if (e.key !== "Tab" || e.key !== "Enter") {
				if (e.key < "a" || e.key > "z") { return; }	//ICI A-Z
			//}
			//let key = "" + e.key;
			//let code = "" + e.code;
			switch (event.target.id) {
				case "nameuser":
					nameSearch();
					break;
				case "pagename":
					pageSearch()
					break;
				default:
					break;
			}

		}

		function pageSearch(){
			let search_after = pagename.value.trim();
			let datalist = document.querySelector("datalist#pageList");
			document.getElementById("pageid").value = "";
			if (search_after.length >1) {
				console.log("->" + search_after);
				let dataid=null;
				try {
					dataid = document.querySelector("datalist#pageList option[value='"+search_after+"']").attributes["data-id"];
				} catch (error) {
					dataid=null;
				}
				if(dataid!=null && parseInt(dataid.value) > 0){
					document.getElementById("pageid").value = dataid.value;
					//AJAX si on voulait faire la 3ieme liste...
				}
			}
		}

		function nameSearch(){
			let search_after = nameuser.value.trim();
			let datalist = document.querySelector("datalist#userList");
			//console.log(search_after + " " + key + " " + code);
			// getElementsByTagName('datalist')[0];
			if (search_after.length >1) {
				console.log("->" + search_after);
				let dataid=null;
				try {
					dataid = document.querySelector("datalist#userList option[value='"+search_after+"']").attributes["data-id"];
				} catch (error) {
					dataid=null;
				}
				if(dataid!=null && parseInt(dataid.value) > 0){
					document.getElementById("nameid").value = dataid.value;
					//AJAX
					let datas = showPages(dataid.value);
					datas.then(pagesUser => {
						//alert(`Full name: ${pagesUser}.`);
						console.log(pagesUser);
						if(pagesUser.length>0){
							//const firstElement = pagesUser[0];
							const firstElement = pagesUser.shift();
							if(firstElement["state"]=='OK'){
								// for (let index = 1; index < pagesUser.length; index++) {
								// 	const element = pagesUser[index];
								// 	console.log(element["pageId"] + "/" + element["pageName"]);
								// 	let options = element.map(o => `<option label="" data-id="${o.pageId}" value="${o.pageName}">`);
								// 	document.getElementById("pageid").append(options);
								// }
								let parentDatalist = document.getElementById("pageList");
								let childArray = parentDatalist.children;
								//let childArray = document.getElementById("userPage").children;
								var cL = childArray.length;
								console.log(cL);
        				while(cL > 0) {
            			cL--;
            			parentDatalist.removeChild(childArray[cL]);
								}
								let options = pagesUser.map(o => `<option label="" data-id="${o.pageId}" value="${o.pageName}">`);
								console.log(options);
								//parentDatalist.append(options);
								//console.log(parentDatalist);
								parentDatalist.innerHTML=options;
								//console.log(parentDatalist.innerHTML);
							}
						}
					});
				}
				//document.getElementById("search").submit();
			}
		}

		document.addEventListener("DOMContentLoaded", function (event) {
			//ICI APRES CHARGEMENT DE LA PAGE COMPLET
			//ICI AJAX FIELDS INIT
			let form = document.getElementsByTagName("form")[0];
			// form.onsubmit = function (e) {
			// 	if
			// 	console.log("SUBMIT CANCEL");
			// 	return false;
			// }
			
			let nameuser = document.getElementById("nameuser");
			nameuser.addEventListener("keyup", doKeyUp, true);
			let pagename = document.getElementById("pagename");
			pagename.addEventListener("keyup", doKeyUp, true);

			nameSearch(); //SI POST ON RELANCE LA RECHERCHE AJAX

			//ICI Code pour reinitialiser les champs du formulaires
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
		});
	</script>
</body>
</html>