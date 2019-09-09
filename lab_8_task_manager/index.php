<?php

/**
 * This is our main controller and URL routing
 */
session_start();

require_once("./config.php");
require_once(ROOT_CGI . "/model/UserManager.php");


function register() {

    $pageDescription = "Registration to TaskManager";
    $pageTitle = "TaskManager - Registrer";

    $userManager = new UserManager();

    if ($userManager->isConnected()) {
        $project = $userManager->getUserProjects();
        require(ROOT_CGI . "/view/userProjects.php");
    } else {
        require(ROOT_CGI . "/view/registration.php");
    }
}

function registerValidate() {

    // We are checking in the controller because we want more fine grained
    // error messages returned to the UI. If we were doing the check in the
    // model, we would have to catch a specific exception without being able to
    // know which exact field failed the validation.
    $all = array(
        "username", "password"
    );
    $jsonAnswer = array();
    $messages = array();

    // Get RAW JSON data
    $query = file_get_contents('php://input');
    $jsonQuery = json_decode($query, true);
    $username = $jsonQuery["username"];
    $password = $jsonQuery["password"];

    $userManager = new UserManager();
    try {
        $userManager->insertNewUser($username, $password);

        $userManager->defineUserSession($username);

        header("Content-type: application/json");
        $jsonAnswer += array("success" => "true");
        $jsonAnswer += array("messages" => $username . " registered with successful");
        echo json_encode($jsonAnswer);
        return;

    } catch (Exception $e) {

        // Do not try to insert if we are running into issues
        header("Content-type: application/json");
        $jsonAnswer += array("success" => "false");
        $jsonAnswer += array("messages" => $e->getMessage());
        echo json_encode($jsonAnswer);
        return;
    }


}

function login() {
    $pageDescription = "Login to TaskManager";
    $pageTitle = "TaskManager - Login";

    require(ROOT_CGI . "/view/login.php");
}

function loginValidate() {

    // We are checking in the controller because we want more fine grained
    // error messages returned to the UI. If we were doing the check in the
    // model, we would have to catch a specific exception without being able to
    // know which exact field failed the validation.
    $all = array(
        "username", "password"
    );
    $jsonAnswer = array();
    $specified = array();
    $messages = array();

    // Get RAW JSON data
    $query = file_get_contents('php://input');
    $jsonQuery = json_decode($query, true);

    $username = $jsonQuery["username"];
    $password = $jsonQuery["password"];

    $userManager = new UserManager();

    if (!$userManager->checkCredentials($username, $password)) {
        header("Content-type: application/json");
        $jsonAnswer += array("success" => "false");
        $jsonAnswer += array("messages" => $messages);
        echo json_encode($jsonAnswer);
        return;
    }

    $userManager->defineUserSession($username);
    header("Content-type: application/json");
    $jsonAnswer += array("success" => "true");
    echo json_encode($jsonAnswer);
}

function viewProjects() {

    $pageDescription = "Projects";
    $pageTitle = "Projects";

    $userManager = new UserManager();

    if ($userManager->isConnected()) {
        $projects = $userManager->getUserProjects();
        var_dump($projects);
        require(ROOT_CGI . "/view/userProjects.php");
    } else {
        require(ROOT_CGI . "/view/registration.php");
    }
}

if (isset($_GET['action'])) {

    if ($_GET['action'] == 'register') {
        register();
    } else if ($_GET['action'] == 'registerValidate') {
        registerValidate();
    } else if ($_GET['action'] == 'login') {
        login();
    } else if ($_GET['action'] == 'loginValidate') {
        loginValidate();
    } else if ($_GET['action'] == 'viewProjects') {
        viewProjects();
    }

// If no action has been determined, go to home
} else {
    require(ROOT_CGI . '/view/home.php');
}
