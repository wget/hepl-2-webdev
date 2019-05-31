<?php

/**
 * This is our main controller and URL routing
 */

require_once("./config.php");
require_once(ROOT_CGI . "/model/Database.php");

function viewDatabase() {

    header("Content-type: application/json");
    $jsonAnswer = array();
    $messages = array();
    $error = false;

    // Get RAW JSON data
    $query = file_get_contents('php://input');
    $jsonQuery = json_decode($query, true);

    if ($jsonQuery === NULL) {
        $jsonAnswer += array("success" => "false");
        $messages[] = "requestMalformed";
        $jsonAnswer += array("messages" => $messages);
        echo json_encode($jsonAnswer);
        return;
    }

    if (empty($jsonQuery["host"])) {
        $messages[] = "databaseMissingRequired";
        $error = true;
    }

    if (empty($jsonQuery["database"])) {
        $messages[] = "databaseMissingRequired";
        $error = true;
    }

    if (empty($jsonQuery["username"])) {
        $messages[] = "usernameMissingRequired";
        $error = true;
    }

    if (empty($jsonQuery["password"])) {
        $messages[] = "passwordMissingRequired";
        $error = true;
    }

    if (empty($jsonQuery["request"])) {
        $messages[] = "requestMissingRequired";
        $error = true;
    }

    if ($error) {
        $jsonAnswer += array("success" => "false");
        $jsonAnswer += array("messages" => $messages);
        echo json_encode($jsonAnswer);
        return;
    }

    $database = Database::getConnection(
        $jsonQuery["host"],
        $jsonQuery["database"],
        $jsonQuery["username"],
        $jsonQuery["password"]);

    try {
        $resultSet = Database::executeRequest($jsonQuery["request"]);
    } catch (Exception $e) {
        $jsonAnswer += array("success" => "false");
        $messages[] = $e->getMessage();
        $jsonAnswer += array("messages" => $messages);
        echo json_encode($jsonAnswer);
        return;
    }

    $jsonAnswer += array("success" => "true");
    $jsonAnswer += array("messages" => $resultSet);
    echo json_encode($jsonAnswer);
}

if (isset($_GET['action'])) {
    // Other routed actions
    if ($_GET['action'] == 'viewDatabase') {
        viewDatabase();
    }

// If no action has been determined, go to registration
} else {
    require(ROOT_CGI . '/view/registration.php');
}
