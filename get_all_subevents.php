<?php
/*
 * Following code will list all the subevents
 */

// array for JSON response
$response = array();

// check for required fields
if (isset($_POST['eventName']) && isset($_POST['startDate'])) {
    $eventName = $_POST['eventName'];
    $startDate = $_POST['startDate'];

// include db connect class
    require_once __DIR__ . '/db_connect.php';

// connecting to db
    $db = new DB_CONNECT();
    $link = $db->connect();

    $eventid_query = "SELECT eventid FROM events WHERE eventname='$eventName' and startdate='$startDate'";

    $selectallquery =
        "SELECT subeventid, agecategoryname, subeventgenderid, gamename, teamof,
        subevents.noofparticipants as subevent_noofparticipants, events.noofparticipants as events_noofparticipants
        FROM subevents
            JOIN events ON subevents.eventid = events.eventid
            JOIN ageCategories ON subevents.agecategoryid = ageCategories.agecategoryid
            JOIN gamesTeamOf ON subevents.gamesteamofid = gamesTeamOf.gamesteamofid
            JOIN games ON games.gameid = gamesTeamOf.gameid
        WHERE events.eventid = ($eventid_query)";

    $result = mysqli_query($link, $selectallquery);

    // check for empty result
    if (mysqli_affected_rows($link) > 0) {
        // looping through all results
        $response["event_subevents"] = array();

        while ($row = mysqli_fetch_array($result)) {
            $event_subevent = array();

            $response['event_noofparticipants'] = $row['events_noofparticipants'];

            $event_subevent['subeventid'] = $row['subeventid'];
            $event_subevent['agecategoryname'] = $row['agecategoryname'];
            $event_subevent['subeventgenderid'] = $row['subeventgenderid'];
            $event_subevent['gamename'] = $row['gamename'];
            $event_subevent['teamof'] = $row['teamof'];
            $event_subevent['subevent_noofparticipants'] = $row['subevent_noofparticipants'];

            // push single event into final response array
            array_push($response["event_subevents"], $event_subevent);
        }
        // success
        $response["success"] = 1;
        // echoing JSON response
        echo json_encode($response);
    } else {
        $response["success"] = 0;
        $response["message"] = "No subevents found for specified event";

        // echo no users JSON
        echo json_encode($response);
    }
}else{
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    // echoing JSON response
    echo json_encode($response);
}


$db->close();
?>