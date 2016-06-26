<?php
/*
 * Following code will list all the events
 */

// array for JSON response
$response = array();

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();
$link = $db->connect();

$selectallquery = "SELECT * FROM events";
$result = mysqli_query($link, $selectallquery);

// check for empty result
if (mysqli_affected_rows($link) > 0) {
    // looping through all results
    $response["events"] = array();

    while ($row = mysqli_fetch_array($result)) {

        $event = array();

        $event['eventName']              = $row['eventName'];
        $event['startDate']              = $row['startDate'];
        $event['endDate']                = $row['endDate'];
        $event['startTime']              = $row['startTime'];
        $event['endTime']                = $row['endTime'];
        $event['venue']                  = $row['venue'];
        $event['noOfParticipants']       = $row['noOfParticipants'];
        $event['isNotified']             = $row['isNotified'];
        $event['isNotifiedforSchedule']  = $row['isNotifiedforSchedule'];

        // push single event into final response array
        array_push($response["events"], $event);
    }
    // success
    $response["success"] = 1;

    // echoing JSON response
    echo json_encode($response);
} else {
    // no event found
    $response["success"] = 0;
    $response["message"] = "No events found";

    // echo no users JSON
    echo json_encode($response);
}
$db->close();
?>