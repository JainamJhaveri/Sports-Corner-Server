<?php

/*
 * Following code will update an event information
 */

// array for JSON response
$response = array();

// check for required fields
if (isset($_POST['newEventName']) && isset($_POST['newStartDate']) && isset($_POST['endDate'])
    && isset($_POST['startTime']) && isset($_POST['endTime']) && isset($_POST['venue'])
    && isset($_POST['noOfParticipants']) && isset($_POST['isNotified'])
    && isset($_POST['isNotifiedforSchedule']) && isset($_POST['oldEventName']) && isset($_POST['oldStartDate'])
) {

    $newEventName           = $_POST['newEventName'];
    $newStartDate           = $_POST['newStartDate'];
    $endDate                = $_POST['endDate'];
    $startTime              = $_POST['startTime'];
    $endTime                = $_POST['endTime'];
    $venue                  = $_POST['venue'];
    $noOfParticipants       = $_POST['noOfParticipants'];
    $isNotified             = $_POST['isNotified'];
    $isNotifiedforSchedule  = $_POST['isNotifiedforSchedule'];
    $oldEventName           = $_POST['oldEventName'];
    $oldStartDate           = $_POST['oldStartDate'];

    // include db connect class
    require_once __DIR__ . '/db_connect.php';

    // connecting to db
    $db = new DB_CONNECT();
    $link = $db->connect();
    // events( eventID, eventName, startDate, endDate, startTime, endTime, venue, noOfParticipants, isNotified, isNotifiedforSchedule )
    $updatequery = "UPDATE events SET eventName='$newEventName', startDate='$newStartDate', 
                                      endDate='$endDate', startTime='$startTime', endTime='$endTime', 
                                      venue='$venue', noOfParticipants='$noOfParticipants', 
                                      isNotified='$isNotified', isNotifiedforSchedule='$isNotifiedforSchedule' 
                                      WHERE (eventName, startDate) = ('$oldEventName',startDate='$oldStartDate')";

    $result = mysqli_query($link, $updatequery);
    // check if row inserted or not
    if (mysqli_affected_rows($link) > 0) {
        // successfully updated
        $response["success"] = 1;
        $response["message"] = "Event successfully updated.";

        // echoing JSON response
        echo json_encode($response);
    } else {
        // No event found with passed eventname and startdate
        $response["success"] = 0;
        $response["message"] = "No event found with given eventname and startdate";

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

//    echo $newEventName . "\n" . $newStartDate . "\n" . $endDate . "\n" . $startTime . "\n" . $endTime . "\n" . $venue . "\n" .
//        $noOfParticipants . "\n" . $isNotified . "\n" .$isNotifiedforSchedule. "\n" . "$oldeventName" . "\n" . "$oldstartDate";

?>
