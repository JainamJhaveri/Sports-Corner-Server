<?php

/*
 * Following code will delete subevent row
 */

// array for JSON response
$response = array();

// check for required fields
if (isset($_POST['eventName']) && isset($_POST['startDate']) && isset($_POST['teamOf'])
    && isset($_POST['gameName']) && isset($_POST['ageCategoryName']) && isset($_POST['subeventGenderID'])) {

    $eventName = $_POST['eventName'];
    $startDate = $_POST['startDate'];
    $teamOf = $_POST['teamOf'];
    $gameName = $_POST['gameName'];
    $ageCategoryName = $_POST['ageCategoryName'];
    $subeventGenderID = $_POST['subeventGenderID'];

    // include db connect class
    require_once __DIR__ . '/db_connect.php';

    // connecting to db
    $db = new DB_CONNECT();
    $link = $db->connect();

//    echo $teamOf." ". $gameName ." ". $eventName ." ". $startDate;
    
    $gameteamofid_query =  "SELECT gamesTeamOfID FROM gamesTeamOf 
                              JOIN games ON gamesTeamOf.gameID = games.gameID
                              WHERE (gameName,teamOf) = ('$gameName', $teamOf)";
    $eventid_query = "SELECT eventID FROM events WHERE (eventName, startDate) = ('$eventName', '$startDate')";
    $agecategory_query = "SELECT ageCategoryID FROM ageCategories WHERE ageCategoryName = '$ageCategoryName'";

//    subevents( subEventID, eventID, ageCategoryID, subEventGenderID, gamesTeamOfID, noOfParticipants, isScheduled )
    $deletequery = "DELETE FROM subevents 
                      WHERE eventID=($eventid_query) AND ageCategoryID=($agecategory_query) AND 
                            subEventGenderID='$subeventGenderID' AND gamesTeamOfID=($gameteamofid_query)";
//                    = ( ($eventid_query), ($agecategory_query), '$subeventGenderID', ($gameteamofid_query) )";

    $result = mysqli_query($link, $deletequery);
    
    if ( mysqli_affected_rows($link) > 0) {
        // successfully inserted into database
        $response["success"] = 1;
        $response["message"] = "Subevent succesfully deleted.";

        // echoing JSON response
        echo json_encode($response);
    } else {
        $selectquery = "SELECT subeventid FROM subevents 
                            WHERE eventID=($eventid_query) AND ageCategoryID=($agecategory_query) AND 
                            subEventGenderID='$subeventGenderID' AND gamesTeamOfID=($gameteamofid_query)";
        $selectresult = mysqli_query($link, $selectquery);

        if (mysqli_affected_rows($link) > 0) {
            // Foreign key reference constraint is violated as a child already exists for the selected event.
            $response["success"] = 0;
            $response["message"] = "One or more participants are entered for the subevent. Hence, subevent can't be deleted";
        } else {
            $response["success"] = 0;
            $response["message"] = "No subevent with specified data is found";
        }
        // echo no users JSON
        echo json_encode($response);
    }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    // echoing JSON response
    echo json_encode($response);
}
$db->close();
?>