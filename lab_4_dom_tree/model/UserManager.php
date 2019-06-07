<?php
require_once(ROOT_CGI . "/model/Database.php");

class UserManager {

    function isEmailUnique($email) {
        $db = Database::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare("select email from user where email = lower(:email)");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($stmt->fetch() === false) {
            return true;
        }
        return false;
    }
}
