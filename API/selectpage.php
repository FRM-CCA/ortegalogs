<?php
header("Content-Type: application/json; charset=UTF-8");
$conn = null;
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "dbtrace";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    $conn = null;
}
$userId = "";
if (!empty($_REQUEST["userid"])) {
    $userId = trim($_REQUEST["userid"]);
}

$dataresult = [];

if (empty($userId)) {
	array_push($dataresult, 
		array( 
			"state" => "KO",
			"nbelmt" => 0,
			"infos" => "no userid"
		)
	);
	echo json_encode($dataresult);
	return;
}

if ($conn != null) {
    try {
        $sql = "SELECT distinct Trace.PageId, Page.Page from Trace inner join Page on Trace.PageId=Page.Id where UserId= :userid order by Page";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":userid", $userId, PDO::PARAM_INT);
        $result = $stmt->execute();
        $rows = $stmt->fetchAll();
        //echo json_encode($rows);
        if ($rows) { //existe deja dans la base
					array_push($dataresult, 
							$myRow=array( 
								"state" => "OK",
								"nbelmt" => count($rows),
								"infos" => ""
							)
					);
					foreach ($rows as $row) {
						$myRow=array( 
							"pageId" => $row["PageId"],
							"pageName" => $row["Page"]
						);
						array_push($dataresult, $myRow);
					}
        }
				else{
					array_push($dataresult, 
							$myRow=array( 
								"state" => "OK",
								"nbelmt" => 0,
								"infos" => ""
							)
					);
				}
    } catch (PDOException $e) {
			$dataresult=[];
			array_push($dataresult, 
					$myRow=array( 
						"state" => "KO",
						"nbelmt" => 0,
						"infos" => $e->getMessage()
					)
			);
    }
}
echo json_encode($dataresult);
$conn = null; //attention librer cnx, sur sur autre base ex: mysqli_close($conn);
