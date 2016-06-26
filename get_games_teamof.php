<?php
/*
 * Following code will list all the gamename and teamof relationships
 */
// array for JSON response
$response = array();

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();
$link = $db->connect();
$getgamesteamof_query = "SELECT gamename, teamof FROM games JOIN gamesTeamOf ON games.gameid = gamesTeamOf.gameid";
$result = mysqli_query($link, $getgamesteamof_query);

// check for empty result
if (mysqli_affected_rows($link) > 0) {
    $response["games_teamof"] = array();

    while ($row = mysqli_fetch_array($result)) {
        // temp user array
        $single_rel = array();
        $single_rel["gamename"] = $row["gamename"];
        $single_rel["teamof"] = $row["teamof"];

        // push single product into final response array
        array_push($response["games_teamof"], $single_rel);
    }
    // success
    $response["success"] = 1;
    $response["message"] = "Success";
    // echoing JSON response
    echo json_encode($response);
} else {
    // no relationships found
    $response["success"] = 0;
    $response["message"] = "No relationships between games and teamof found";
    // echo no users JSON
    echo json_encode($response);
}
?>