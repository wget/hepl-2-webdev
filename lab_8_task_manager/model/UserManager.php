<?php
require_once(ROOT_CGI . "/model/Database.php");

class UserManager {

    function isConnected() {
        if (isset($_SESSION["connected"]) == true && isset($_SESSION["userId"])) {
            return true;
        }
        return false;
    }

    function checkCredentials($username, $password) {
        $db = Database::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare("select * from users where username = :username and password = password(:password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($stmt->fetch() === false) {
            return false;
        }
        return true;
    }

    function defineUserSession($username) {
        $db = Database::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare("select id from users where username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $userId = $stmt->fetch();
        $_SESSION["userId"] = $userId;
        $_SESSION["connected"] = true;
    }

    function isUserConnected() {
        return $_SESSION["connected"] == true ? true : false;
    }

    function getUserConnected() {
        return $_SESSION["userId"];
    }

    function removeUserSession() {
        unset($_SESSION["userId"]);
        $_SESSION["connected"] = false;
    }

    function insertNewUser($username, $password) {
        if (!$this->isUsernameUnique($username)) {
            throw new Exception("Username \"" . $username . "\" already exists");
        }
        $db = database::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare("insert into users(username, password) values (:username, password(:password))");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
    }

    function createProject($description, $manager) {
        $db = database::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare("insert into projects(description, manager) values (:description, :manager)");
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':manager', $manager);
        $stmt->execute();
    }

    function getUserProjects() {
        $db = database::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare("select description from projects where manager = :manager order by id");
        $manager = $_SESSION["userId"];
        $stmt->bindParam(':manager', $manager);
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }

    
    function isUsernameValid($username) {
        // In order to avoid being interpretated as a range, the dash must be
        // double escaped.
        return preg_match('/^([A-Za-z0-9 _\\-.\']+)$/i', $username) > 0 ? true : false;
    }

    function isUsernameUnique($username) {
        $db = Database::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare("select username from users where username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($stmt->fetch() === false) {
            return true;
        }
        return false;
    }

    function isEmailUnique($email) {
        $db = Database::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare("select email from profile_user_session where email = lower(:email)");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($stmt->fetch() === false) {
            return true;
        }
        return false;
    }

    function isOriginCityValid($originCity) {
        return preg_match('/^([A-Za-z0-9 _-.\']+)$/i', $username) > 0 ? true : false;
    }

    function isBioValid($bio) {
        return preg_match('/^([A-Za-z0-9 _-.\']+)$/i', $username) > 0 ? true : false;
    }

    // function registerValidate() {
    //
    // }
    //
}
