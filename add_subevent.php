<?php

/*
 * Following code will create a new subevent row
 * All product details are read from HTTP Post Request
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

    $gameteamofid_query =  "SELECT gamesTeamOfID FROM gamesTeamOf JOIN games 
                          ON gamesTeamOf.gameID = games.gameID
                          WHERE (gameName,teamOf) = ('$gameName', $teamOf)";
    $eventid_query = "SELECT eventID FROM events WHERE (eventName, startDate) = ('$eventName', '$startDate')";
    $agecategory_query = "SELECT ageCategoryID FROM ageCategories WHERE ageCategoryName = '$ageCategoryName'";


//    subevents( subEventID, eventID, ageCategoryID, subEventGenderID, gamesTeamOfID, noOfParticipants, isScheduled )
    $insertquery = "INSERT INTO
                    subevents( eventID, ageCategoryID, subEventGenderID, gamesTeamOfID, noOfParticipants, isScheduled )
                    VALUES( ($eventid_query), ($agecategory_query), '$subeventGenderID', ($gameteamofid_query), 0, false)";

    $result = mysqli_query($db->connect(), $insertquery);

    // check if row inserted or not
    if (mysqli_num_rows($result)) {
        // successfully inserted into database
        $response["success"] = 1;
        $response["message"] = "Subevent inserted into db.";

        // echoing JSON response
        echo json_encode($response);
    } else {
        // failed to insert row
        $response["success"] = 0;
        $response["message"] = "Oops! An error occurred.";

        // echoing JSON response
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