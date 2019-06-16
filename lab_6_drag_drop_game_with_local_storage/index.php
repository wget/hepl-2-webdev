<?php

/**
 * This is our main controller and URL routing
 */

require_once("./config.php");
require_once(ROOT_CGI . "/model/UserManager.php");

function checkEmail() {

    header("Content-type: application/json");
    $jsonAnswer = array();
    $messages = array();

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

    if (empty($jsonQuery["email"])) {
        $jsonAnswer += array("success" => "false");
        $messages[] = "emailMissingRequired";
        $jsonAnswer += array("messages" => $messages);
        echo json_encode($jsonAnswer);
        return;
    }

    $userManager = new UserManager();

    if (!filter_var($jsonQuery["email"], FILTER_VALIDATE_EMAIL)) {
        $jsonAnswer += array("success" => "false");
        $messages[] = "emailInvalid";
        $jsonAnswer += array("messages" => $messages);
        echo json_encode($jsonAnswer);
        return;
    }

    if (!$userManager->isEmailUnique($jsonQuery["email"])) {
        $jsonAnswer += array("success" => "false");
        $messages[] = "emailNotUnique";
        $jsonAnswer += array("messages" => $messages);
        echo json_encode($jsonAnswer);
        return;
    }

    // Simulate delay
    sleep(5);

    $jsonAnswer += array("success" => "true");
    echo json_encode($jsonAnswer);
}

if (isset($_GET['action'])) {
    // Other routed actions
    if ($_GET['action'] == 'checkEmail') {
        checkEmail();
    }

// If no action has been determined, go to registration
} else {
    require(ROOT_CGI . '/view/registration.php');
}
