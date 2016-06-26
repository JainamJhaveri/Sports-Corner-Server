<?php

/*
 * Following code will delete an event from table
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
//        Night cricket 2015-01-02

    $deletequery = "DELETE FROM events WHERE eventName = '$eventName' AND startDate='$startDate'";
    $result = mysqli_query($link, $deletequery);

    if (mysqli_affected_rows($link) > 0) {
        $response["success"] = 1;
        $response["message"] = "Event successfully deleted." . mysqli_affected_rows($link);

        // echoing JSON response
        echo json_encode($response);
    } else {
        $selectquery = "SELECT eventid FROM events WHERE eventName = '$eventName' AND startDate='$startDate'";
        $selectresult = mysqli_query($link, $selectquery);

        if (mysqli_affected_rows($link) > 0) {
            // Foreign key reference constraint is violated as a child already exists for the selected event.
            $response["success"] = 0;
            $response["message"] = "One or more subevents exists for this event. Hence, event can't be deleted";
        } else {
            $response["success"] = 0;
            $response["message"] = "No event with specified data is found";
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