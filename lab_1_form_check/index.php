<?php

/**
 * This is our main controller and URL routing
 */

require_once("./config.php");

if (isset($_GET['action'])) {
    // Other routed actions

// If no action has been determined, go to registration
} else {
    require(ROOT_CGI . '/view/registration.php');
}
