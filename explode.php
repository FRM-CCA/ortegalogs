<?php
//dans les autres langages c'est split()

// $line=" - Client = 91-168-93-143.subs.proxad.net - IP = 91.168.93.143 - FormateurWiki - PagePrincipale - Nous sommes le 07-01-2023 et il est 20:09";

// $tabline = explode("- ", $line);
// echo $tabline[0]."<br>";
// echo $tabline[1]."<br>";
// $host = trim(str_replace("Client = ", "", $tabline[1]));
// echo "-->" .$host."<br>";
// echo $tabline[2]."<br>";
// $ip =trim(str_replace("IP = ", "", $tabline[2]));
// echo "-->" .$ip."<br>";
// echo $tabline[3]."<br>";
// $user = trim($tabline[3]);
// echo "-->" . $user ."<br>";
// echo trim($tabline[4])."<br>";
// $page = trim($tabline[4]);
// echo "-->" .$page."<br>";
// echo $tabline[5]."<br>";
// $date = explode(" et ", $tabline[5]);
// echo $date[0]."<br>";
// echo $date[1]."<br>";
// $time = trim(str_replace("il est ", "", $date[1]));
// $time .= ":00";
// echo "-->" .$time."<br>";
// $datetime = trim(str_replace("Nous sommes le ", "", $date[0]));
// echo "-->" .$datetime."<br>";
// $datetime = explode("-", $datetime);
// echo "-->" .$datetime[0]."<br>";
// echo "-->" .$datetime[1]."<br>";
// echo "-->" .$datetime[2]."<br>";
// $datetimef = $datetime[2] . "-" . $datetime[1] ."-" . $datetime[0] ." " . $time ;
// echo "-->" .$datetimef ."<br>";

for ($i=0; $i < 6; $i++) { 
	if( $i%2==0){
		$line=" - Client = 91-168-93-143.subs.proxad.net - IP = 91.168.93.143 - FormateurWiki - PagePrincipale - Nous sommes le 07-01-2023 et il est 20:09";
	}
	else{
		$line="-----------------------------------";
	}

	//if(!str_starts_with($line, "-------")){
	if(str_starts_with($line, "-------")==false){
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
	}
	else{
		echo "<h2>pas bonne ligne</h2>";
	}
}
