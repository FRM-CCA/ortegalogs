<h2>Via une ligne de trace</h2>
<?php
//dans d'autres langages c'est split()

$line=" - Client = 91-168-93-143.subs.proxad.net - IP = 91.168.93.143 - FormateurWiki - PagePrincipale - Nous sommes le 07-01-2023 et il est 20:09";
$tabline = explode("- ", $line);
echo $tabline[0]."<br>";
echo $tabline[1]."<br>";
$host = trim(str_replace("Client = ", "", $tabline[1]));
echo "-->" .$host."<br>";
echo $tabline[2]."<br>";
$ip =trim(str_replace("IP = ", "", $tabline[2]));
echo "-->" .$ip."<br>";
echo $tabline[3]."<br>";
$user = trim($tabline[3]);
echo "-->" . $user ."<br>";
echo trim($tabline[4])."<br>";
$page = trim($tabline[4]);
echo "-->" .$page."<br>";
echo $tabline[5]."<br>";
$date = explode(" et ", $tabline[5]);
echo $date[0]."<br>";
echo $date[1]."<br>";
$time = trim(str_replace("il est ", "", $date[1]));
$time .= ":00";
echo "-->" .$time."<br>";
$datetime = trim(str_replace("Nous sommes le ", "", $date[0]));
echo "-->" .$datetime."<br>";
$datetime = explode("-", $datetime);
echo "-->" .$datetime[0]."<br>";
echo "-->" .$datetime[1]."<br>";
echo "-->" .$datetime[2]."<br>";
$datetimef = $datetime[2] . "-" . $datetime[1] ."-" . $datetime[0] ." " . $time ;
echo "-->" .$datetimef ."<br>";

?>
<h2>Via simulation de 4 lignes</h2>
<?php
for ($i = 0; $i < 4; $i++) { ///ici on simule 4 lignes
 if ($i % 2 == 0) { //modulo 2 (comme en python, merci permet de savoir si nombre pair, souvent utilisé pour calculer 1 ligne sur 2)
  $line = " - Client = 91-168-93-143.subs.proxad.net - IP = 91.168.93.143 - FormateurWiki - PagePrincipale - Nous sommes le 07-01-2023 et il est 20:09";
 } else {
  $line = "-----------------------------------";
 }

 //if(!str_starts_with($line, "-------")){
 if (str_starts_with($line, "-------") == false) {
  $tabline = explode("- ", $line);
  echo $tabline[0] . "<br>";
  echo $tabline[1] . "<br>";
  $host = trim(str_replace("Client = ", "", $tabline[1]));
  echo "-->" . $host . "<br>";
  echo $tabline[2] . "<br>";
  $ip = trim(str_replace("IP = ", "", $tabline[2]));
  echo "-->" . $ip . "<br>";
  echo $tabline[3] . "<br>";
  $user = trim($tabline[3]);
  echo "-->" . $user . "<br>";
  echo trim($tabline[4]) . "<br>";
  $page = trim($tabline[4]);
  echo "-->" . $page . "<br>";
  echo $tabline[5] . "<br>";
  $date = explode(" et ", $tabline[5]);
  echo $date[0] . "<br>";
  echo $date[1] . "<br>";
  $time = trim(str_replace("il est ", "", $date[1]));
  $time .= ":00";
  echo "-->" . $time . "<br>";
  $datetime = trim(str_replace("Nous sommes le ", "", $date[0]));
  echo "-->" . $datetime . "<br>";
  $datetime = explode("-", $datetime);
  echo "-->" . $datetime[0] . "<br>";
  echo "-->" . $datetime[1] . "<br>";
  echo "-->" . $datetime[2] . "<br>";
  $datetimef = $datetime[2] . "-" . $datetime[1] . "-" . $datetime[0] . " " . $time;
  echo "-->" . $datetimef . "<br>";
 } else {
  echo "<h2>pas bonne ligne</h2>";
 }
}
?>
<h2>Via Fichier de trace</h2>
<?php
$file = null;
$cpt = 0;
$host = $ip = $user = $page = $datetimef = "";
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
    insert2Db($host, $ip, $user, $page, $datetimef);
   }
  }
  fclose($file);
 }
} catch (Exception $e) {
 echo "Exception : ", $e->getMessage(), "\n";
} finally {
 $file = null;
}

function decoupage($line, $cpt)
{
 global $host, $ip, $user, $page, $datetimef;
 //on ne traite pas que la ligne voulu
 if (str_starts_with($line, " - Client =")) { //ou str_contains en php7
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

function insert2Db($host, $ip, $user, $page, $datetime)
{
  //d'abord User/Page et ensuite Trace
}
