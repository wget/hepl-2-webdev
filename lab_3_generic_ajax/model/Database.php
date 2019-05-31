<?php

class Database {
    private static $instance = null;
    private static $connection;

    // The constructor is private
    // to prevent initiation with outer code.
    // Only one private constructor allowed.
    private function __construct($host, $database, $username, $password) {
        self::$connection = new PDO(
            "mysql:host=" . $host . ";dbname=" . $database,
            $username,
            $password);
    }

    // The object is created from within the class itself
    // only if the class has no instance.
    // Can ony have one static method having the same name
    public static function getConnection(
        $host = "localhost",
        $database = "DATABASE_NAME",
        $username = "DATABASE_USERNAME",
        $password = "DATABASE_PASSWORD") {

        if (self::$instance == null) {
            self::$instance = new DataBase($host, $database, $username, $password);
        }

        return self::$connection;
    }

    // This will likely throw exceptions
    public static function executeRequest($requestString) {
        $db = database::getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare($requestString);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }
}
