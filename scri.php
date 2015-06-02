<?php

include("connection.php");

$RequestType = $_REQUEST['RequestType'];
switch ($RequestType) {
    case 1:
        $Email = $_REQUEST['Email'];
        $firstName = $_REQUEST['FirstName'];
        $lastName = $_REQUEST['LastName'];
        $nickname = $_REQUEST['Nickname'];
        $image = $_REQUEST['Image'];
        $password = $_REQUEST['Password'];
        $status = $_REQUEST['Status'];
        DataUtil::RegisterUser($firstName, $Email, $image, $lastName, $nickname, $password, $status);
        break;
    case 2:
        $Email = $_REQUEST['Email'];
        $password = $_REQUEST['Password'];
        DataUtil::LoginUser($Email, $password);
        break;
    case 3:
        $CatID = $_REQUEST['CategoryID'];
        DataUtil::GetGameInformation($CatID);
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
    case 9:
        $UserID = $_REQUEST['UserID'];
        $CategoryID = $_REQUEST['CategoryID'];
        DataUtil::GetAvailablePlayers($UserID, $CategoryID);
        break;
}

class DataUtil {

    public function __construct() {
        
    }

    public static function RegisterUser($firstName, $email, $image, $lastName, $nickname, $password, $status) {
        // $response["User"] = array();

        $sql = mysql_query("INSERT INTO User(firstName, email, image, lastName,nickname, password, status) 
	                   VALUES('$firstName', '$email', '$image', '$lastName', '$nickname', '$password', '$status')");
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

        $sql = mysql_query("INSERT INTO Matchs(catID,challengerID,opponentID,dateTimeStamp) 
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

        $sql = mysql_query("INSERT INTO Scoreboard(point,questionID,scoreDate,matchsID) 
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

        $sql = mysql_query("INSERT INTO Leaderboard(userID,point,reward) 
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
        $result = mysql_query("UPDATE Scoreboard SET point = '$point', scoreDate='$scoreDate' WHERE scoreID = '$scoreID'");
        $row = "";
        if ($result) {
            $sql = mysql_query("SELECT * FROM Scoreboard WHERE scoreID = '$scoreID'");
            while ($scoreBoardResult = mysql_fetch_assoc($sql)) {
                $row["ScoreBoard"][] = $scoreBoardResult;
            }
        }
        echo json_encode($row, JSON_PRETTY_PRINT);
    }

    public static function UpdateLearderBoard($leaderID, $point, $reward) {
        $result = mysql_query("UPDATE Leaderboard SET point = '$point', reward='$reward' WHERE leaderID = '$leaderID'");
        $row = "";
        if ($result) {
            $sql = mysql_query("SELECT * FROM Leaderboard WHERE leaderID = '$leaderID'");
            while ($LeaderResult = mysql_fetch_assoc($sql)) {
                $row["LeaderBoard"][] = $LeaderResult;
            }
        }
        echo json_encode($row, JSON_PRETTY_PRINT);
    }

    //Case 3
    public static function GetGameInformation($CatID) {
        $row = array();
        $sqlQ = mysql_query("SELECT * FROM Question WHERE Question.CatID = '$CatID' ORDER BY RAND() LIMIT 5");
        if (mysql_num_rows($sqlQ) > 0) {
            while ($resultQ = mysql_fetch_assoc($sqlQ)) {

                $row["Question"][] = $resultQ;
            }
        }
        echo json_encode($row, JSON_PRETTY_PRINT);
    }

    //Case 9
    public static function GetAvailablePlayers($UserID, $CategoryID) {
        $sql = mysql_query("SELECT AvailablePlayer.*, User.* FROM AvailablePlayer LEFT JOIN User ON AvailablePlayer.PlayerID = User.UserID WHERE CategoryID = '$CategoryID' AND AvailablePlayer.PlayerID <> '$UserID' AND AvailablePlayer.Status = 'Ok' ORDER BY AvailablePlayer.DateAndTime ASC LIMIT 1");
        $row = "";
        $OpponentID = 0;
        if (mysql_num_rows($sql) > 0) {
            while ($result = mysql_fetch_assoc($sql)) {
                $row["Category"][] = $result;
                $OpponentID = $result['PlayerID'];
            }
            mysql_query("UPDATE AvailablePlayer SET Status = 'Busy' WHERE PlayerID ='$OpponentID'");
        }
        echo json_encode($row, JSON_PRETTY_PRINT);
    }

}

?>