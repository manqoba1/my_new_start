<?php

/*
 * Following code will get single user details
 * A user is userIDentified by user userID (puserID)
 */

// array for JSON response
$response = array();
// check for require fields
// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();

// check for post data
$RequestType = $_REQUEST['RequestType'];
mysql_query("SET NAMES 'UTF8'");
switch ($RequestType) {
    case 1:
        $userName = $_REQUEST['UserName'];
        $email = $_REQUEST['Email'];
        $image = $_REQUEST['Image'];
        $gender = $_REQUEST['Gender'];
        $age = $_REQUEST['Age'];
        $registeredDate = date('Y-m-d H:i:s');
        $password = $_REQUEST['Password'];
        $status = $_REQUEST['Status'];
        DataUtil::RegisterUser($userName, $email, $image, $gender, $age, $registeredDate, $password, $status);
        break;
    case 2:
        $email = $_REQUEST['Email'];
        $password = $_REQUEST['Password'];
        DataUtil::LoginUser($email, $password);
        break;
    case 3:
        $UserID = $_REQUEST['UserID'];
        DataUtil::GetGameData($UserID);
        break;
    case 4:
        $catID = $_REQUEST['CatID'];
        $challengerID = $_REQUEST['ChallengerID'];
        $opponentID = $_REQUEST['OpponentID'];
        $dateTimeStamp = $_REQUEST['DateTimeStamp'];
        DataUtil::CreateMatch($catID, $challengerID, $opponentID, $dateTimeStamp);
        break;
    case 5:
        $point = $_REQUEST['Point'];
        $questionID = $_REQUEST['QuestionID'];
        $scoreDate = $_REQUEST['ScoreDate'];
        $matchsID = $_REQUEST['MatchsID'];

        DataUtil::NewScoreBoard($point, $questionID, $scoreDate, $matchsID);
        break;
    case 6:
        $userID = $_REQUEST['UserID'];
        $point = $_REQUEST['Point'];
        $reward = $_REQUEST['Reward'];

        DataUtil::NewLeaderBoard($userID, $point, $reward);
        break;
    case 7:
        $scoreID = $_REQUEST['ScoreID'];
        $point = $_REQUEST['Point'];
        $scoreDate = $_REQUEST['ScoreDate'];

        DataUtil::UpdateScoreBoard($scoreID, $point, $scoreDate);
        break;
    case 8:
        $leaderID = $_REQUEST['LeaderID'];
        $point = $_REQUEST['Point'];
        $reward = $_REQUEST['Reward'];

        DataUtil::UpdateLearderBoard($leaderID, $point, $reward);
        break;
    default;
}

class DataUtil {

    public function __construct() {
        
    }

    public static function RegisterUser($userName, $email, $image, $gender, $age, $registeredDate, $password, $status) {
        // $response["User"] = array();

        $sql = mysql_query("INSERT INTO User(userName,email,image,gender,age,registeredDate,password,status) 
	                   VALUES('$userName','$email', '$image','$gender','$age','$registeredDate','$password','$status')");
        echo mysql_error();
        $row = "";
        if ($sql) {
            $row["success"] = 1;
            $row["message"] = "Registration successful, Please Check Your Email.";
            echo json_encode($row, JSON_PRETTY_PRINT);
        } else {
            $row["success"] = 0;
            $row["message"] = "Registration unsuccessful, Please Check Your Email.";
            echo json_encode($row, JSON_PRETTY_PRINT);
        }
    }

    public static function CreateMatch($catID, $challengerID, $opponentID, $dateTimeStamp) {
        // $response["User"] = array();

        $sql = mysql_query("INSERT INTO matchs(catID,challengerID,opponentID,dateTimeStamp) 
	                   VALUES('$catID','$challengerID', '$opponentID','$dateTimeStamp')");
        echo mysql_error();
        $row = "";
        if ($sql) {
            $row["success"] = 1;
            $row["message"] = "Match created successful";
            echo json_encode($row, JSON_PRETTY_PRINT);
        } else {
            $row["success"] = 0;
            $row["message"] = "Registration unsuccessful, Please Check Your Email.";
            echo json_encode($row, JSON_PRETTY_PRINT);
        }
    }

    public static function NewScoreBoard($point, $questionID, $scoreDate, $matchsID) {
        // $response["User"] = array();

        $sql = mysql_query("INSERT INTO scoreboard(point,questionID,scoreDate,matchsID) 
	                   VALUES('$point','$questionID', '$scoreDate','$matchsID')");
        echo mysql_error();
        $row = "";
        if ($sql) {
            $row["success"] = 1;
            $row["message"] = "Match created successful";
            echo json_encode($row, JSON_PRETTY_PRINT);
        } else {
            $row["success"] = 0;
            $row["message"] = "Registration unsuccessful, Please Check Your Email.";
            echo json_encode($row, JSON_PRETTY_PRINT);
        }
    }

    public static function NewLeaderBoard($userID, $point, $reward) {
        // $response["User"] = array();

        $sql = mysql_query("INSERT INTO leaderboard(userID,point,reward) 
	                   VALUES('$userID','$point', '$reward')");
        echo mysql_error();
        $row = "";
        if ($sql) {
            $row["success"] = 1;
            $row["message"] = "Match created successful";
            echo json_encode($row, JSON_PRETTY_PRINT);
        } else {
            $row["success"] = 0;
            $row["message"] = "Registration unsuccessful, Please Check Your Email.";
            echo json_encode($row, JSON_PRETTY_PRINT);
        }
    }

    public static function LoginUser($email, $password) {
        $sql = mysql_query("SELECT * FROM User WHERE email = '$email' and password = '$password'");
        $row = "";
        if (mysql_num_rows($sql) > 0) {
            while ($result = mysql_fetch_assoc($sql)) {
                $row["User"][] = $result;
            }
            $row["success"] = 1;
        }
        echo json_encode($row, JSON_PRETTY_PRINT);
    }

    public static function UpdateScoreBoard($scoreID, $point, $scoreDate) {
        $result = mysql_query("UPDATE scoreboard SET point = '$point', scoreDate='$scoreDate' WHERE scoreID = '$scoreID'");
        $row = "";
        if ($result) {
            $sql = mysql_query("SELECT * FROM scoreboard WHERE scoreID = '$scoreID'");
            while ($scoreBoardResult = mysql_fetch_assoc($sql)) {
                $row["ScoreBoard"][] = $scoreBoardResult;
            }
        }
        echo json_encode($row, JSON_PRETTY_PRINT);
    }

    public static function UpdateLearderBoard($leaderID, $point, $reward) {
        $result = mysql_query("UPDATE leaderboard SET point = '$point', reward='$reward' WHERE leaderID = '$leaderID'");
        $row = "";
        if ($result) {
            $sql = mysql_query("SELECT * FROM leaderboard WHERE leaderID = '$leaderID'");
            while ($LeaderResult = mysql_fetch_assoc($sql)) {
                $row["LeaderBoard"][] = $LeaderResult;
            }
        }
        echo json_encode($row, JSON_PRETTY_PRINT);
    }

    public static function GetGameData($UserID) {
        $sql = mysql_query("SELECT * FROM leaderboard order by point DESC");
        $row = array();
        if (mysql_num_rows($sql) > 0) {
            while ($result = mysql_fetch_assoc($sql)) {
                $row["LeaderBoard"][] = $result;
                /* $catID = $row["catID"];
                  $sql2 = mysql_query("SELECT * FROM category where catID = '$catID'");
                  $result2 = mysql_fetch_assoc($sql2);
                  $row["categoryName"] = $result2["categoryName"]; */
            }
        }
        $sqlQ = mysql_query("SELECT * FROM question");
        if (mysql_num_rows($sqlQ) > 0) {
            while ($resultQ = mysql_fetch_assoc($sqlQ)) {

                $row["Question"][] = $resultQ;
                /* $catID = $resultQ["catID"];
                  $sql2 = mysql_query("SELECT * FROM category where catID = '$catID'");
                  $result2 = mysql_fetch_assoc($sql2);
                  $row["categoryName"] = $result2["categoryName"]; */
            }
        }
        $sqlT = mysql_query("SELECT * FROM tip");
        if (mysql_num_rows($sqlT) > 0) {
            while ($resultT = mysql_fetch_assoc($sqlT)) {
                $row["Tip"][] = $resultT;
            }
        }
        $sqlT = mysql_query("SELECT * FROM category");
        if (mysql_num_rows($sqlT) > 0) {
            while ($resultT = mysql_fetch_assoc($sqlT)) {
                $row["Category"][] = $resultT;
            }
        }
        echo json_encode($row, JSON_PRETTY_PRINT);
    }

}

?>