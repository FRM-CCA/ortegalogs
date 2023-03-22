<h2>Via Fichier de trace</h2>
<?php
// Files & variables
$file = null;
$cpt = 0;
$host = $ip = $user = $page = $datetimef = "";
// Db Cnx
$conn = null;
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "dbtrace";

try {
 $file = fopen("file/trace.txt", "r");
 if ($file != null) {
  while (!feof($file)) {
   $cpt += 1;
   $host = $ip = $user = $page = $datetimef = "";
   $line = fgets($file);
   echo $cpt . " : " . $line . "<br />";
   if (decoupage($line, $cpt)) {
    echo $cpt . " : $host/$ip/$user/$page/$datetimef<br>";
    //Voilà on peut gérer le stockage des données
    if(insert2Db($host, $ip, $user, $page, $datetimef)){
      echo $cpt." insert ok<br>";
    }
    else{
      echo $cpt." insert ko<br>";
    }

   }
  }
  fclose($file);
 }
} catch (Exception $e) {
 echo "Exception : ", $e->getMessage(), "\n";
} finally {
 $file = null;
 dbClose();
}

function decoupage($line, $cpt){
 global $host, $ip, $user, $page, $datetimef;
 //on ne traite pas que la ligne voulu
 if (str_starts_with($line, " - Client =")) {
  $tabline = explode("- ", $line);
  $host = trim(str_replace("Client = ", "", $tabline[1]));
  $ip = trim(str_replace("IP = ", "", $tabline[2]));
  $user = trim($tabline[3]);
  $page = trim($tabline[4]);
  $date = explode(" et ", $tabline[5]);
  $time = trim(str_replace("il est ", "", $date[1]));
  $time .= ":00";
  $datetime = trim(str_replace("Nous sommes le ", "", $date[0]));
  $datetime = explode("-", $datetime);
  $datetimef = $datetime[2] . "-" . $datetime[1] . "-" . $datetime[0] . " " . $time;
  return true;
 }
 return false;
}

function dbConnect(){
  global $conn, $servername, $username, $password, $dbname;
  if($conn==null){
    try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      echo "Connected successfully<br>";
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage() ."<br>";
      dbClose();
      return false;
    }
  }
  return true;
}

function dbClose(){
  global $conn;
  $conn=null;
}

function insert2Db($host, $ip, $user, $page, $datetime)
{
 //d'abord User/Page et ensuite Trace
 global $conn;
 $pageId=$userId=$traceId=-1;
 //global $conn, $servername, $username, $password, $dbname;
 if(dbConnect()==false)
  return false;

 if ($conn == null) {
  return false;
 }
 else{
  try {
    //Table Page
    $sql = "select Id from Page where Page = :page";
    $stmt = $conn->prepare($sql);
    // switch($val->type){
    //   case \PDO::PARAM_STR: $type = 'string'; break;
    //   case \PDO::PARAM_BOOL: $type = 'boolean'; break;
    //   case \PDO::PARAM_INT: $type = 'integer'; break;
    //   case \PDO::PARAM_NULL: $type = 'null'; break;
    //   default: $type = FALSE;
    // }
    //$sth->bindParam(':calories', $calories, PDO::PARAM_INT);
    //$sth->bindValue(":page", $page, PDO::PARAM_STR, 12);
    $stmt->bindValue(":page", $page, PDO::PARAM_STR);
    $result = $stmt->execute();
    $rows = $stmt->fetchAll();
    if($rows){  //existe deja dans la base
      foreach ($rows as $row) {
        $pageId = $row["Id"]; 
      }
    }
    else{   //n'existe deja dans la base, donc on le rajoute
      $sql = "INSERT INTO `page`(`Id`, `Page`, `exclude`) VALUES (null,:page, 0)";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":page", $page, PDO::PARAM_STR);
      $result = $stmt->execute();
      if($result){
        $pageId = $conn->lastInsertId(); //INSERT
        //echo 'idPage:'.$pageId;
      }
    }
    //on a donc le pageId

    //Table User
    if (trim($user) == ""){ //en sql pas de vide mais le mot "null" (ou empty($user))
     $userId="null";
    }
    else{
      $sql = "select Id from User where Name = :user";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":user", $user, PDO::PARAM_STR);
      $result = $stmt->execute();
      $rows = $stmt->fetchAll();
      if($rows){  //existe deja dans la base
        foreach ($rows as $row) {
          $userId = $row["Id"]; 
        }
      }
      else{   //n'existe deja dans la base, donc on le rajoute
        $sql = "INSERT INTO `user`(`Id`, `DateCreation`, `Name`) VALUES (null, CURRENT_TIMESTAMP(), :user)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":user", $user, PDO::PARAM_STR);
        $result = $stmt->execute();
        if($result){
          $userId = $conn->lastInsertId(); //INSERT
          //echo 'idUser:'.$userId;
        }
      }
      //on a donc le userId
      $sql = "INSERT INTO `trace`(`Ip`, `Host`, `DateCnx`, `UserId`, `PageId`) 
        VALUES (:ip, :host, :datecnx, :userid, :pageid)";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":ip", $ip, PDO::PARAM_STR);
      $stmt->bindValue(":host", $host, PDO::PARAM_STR);
      $stmt->bindValue(":datecnx", $datetime, PDO::PARAM_STR);
      $stmt->bindValue(":userid", $userId, PDO::PARAM_INT);
      $stmt->bindValue(":pageid", $pageId, PDO::PARAM_INT);
      $result = $stmt->execute();
      if($result){
        $traceId = $conn->lastInsertId(); //INSERT
        //echo 'idTrace:'.$traceId;
      }
      echo "### idTrace:".$traceId . " /idUser:".$userId . " /idPage:".$pageId."<br>";
    }
  } catch (PDOException $e) {
   echo $sql . "<br>" . $e->getMessage();
   return false;
  }
 }
 return true;
}
