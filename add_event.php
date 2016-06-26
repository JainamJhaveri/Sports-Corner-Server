<?php

/*
 * Following code will create a new event row
 * All product details are read from HTTP Post Request
 */

// TODO: DATE FORMAT SHOULD BE RECEIVED AS YYYY-DD-MM for insertion in mysql table

// array for JSON response
$response = array();

// check for required fields
if (isset($_POST['eventName']) && isset($_POST['startDate']) && isset($_POST['endDate'])
    && isset($_POST['startTime']) && isset($_POST['endTime']) && isset($_POST['venue'])
    && isset($_POST['noOfParticipants']) && isset($_POST['isNotified'])
    && isset($_POST['isNotifiedforSchedule'])) {

    $eventName              = $_POST['eventName'];
    $startDate              = $_POST['startDate'];
    $endDate                = $_POST['endDate'];
    $startTime              = $_POST['startTime'];
    $endTime                = $_POST['endTime'];
    $venue                  = $_POST['venue'];
    $noOfParticipants       = $_POST['noOfParticipants'];
    $isNotified             = $_POST['isNotified'];
    $isNotifiedforSchedule  = $_POST['isNotifiedforSchedule'];

    // include db connect class
    require_once __DIR__ . '/db_connect.php';

    // connecting to db
    $db = new DB_CONNECT();
    $link = $db->connect();
    // mysql inserting a new row
    // events( eventID, eventName, startDate, endDate, startTime, endTime, venue, noOfParticipants, isNotified, isNotifiedforSchedule )

    $insertquery = "INSERT INTO 
                      events( eventName, startDate, endDate, startTime, endTime, 
                            venue, noOfParticipants, isNotified, isNotifiedforSchedule ) 
                            VALUES( '$eventName', '$startDate', '$endDate', '$startTime', '$endTime', 
                              '$venue', '$noOfParticipants', '$isNotified', '$isNotifiedforSchedule' )";
    $result = mysqli_query($link, $insertquery);

    // check if row inserted or not
    if (mysqli_affected_rows($link) > 0) {
        // successfully inserted into database
        $response["success"] = 1;
        $response["message"] = "Event inserted into db.";

        // echoing JSON response
        echo json_encode($response);
    } else {
        $selectquery = "SELECT * FROM events WHERE ( eventName, startDate, endDate, startTime, endTime, 
                            venue, noOfParticipants, isNotified, isNotifiedforSchedule ) 
                            = ( '$eventName', '$startDate', '$endDate', '$startTime', '$endTime', 
                              '$venue', '$noOfParticipants', '$isNotified', '$isNotifiedforSchedule' )";
        $selectresult = mysqli_query($link, $selectquery);

        if( mysqli_affected_rows($link) > 0) {
            // failed to insert row
            $response["success"] = 0;
            $response["message"] = "Event with same startDate and eventName already exists in the database.";
        }
        else{
            // failed to insert row
            $response["success"] = 0;
            $response["message"] = "Oops! An error occurred.";
        }
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