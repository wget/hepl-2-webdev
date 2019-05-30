<?php
require_once("../config.php");
require_once("../model/Database.php");

$db = Database::getConnection();
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $db->prepare("insert into user(username, email, salt, password) values (:username, :email, :salt, :password)");
// bindParam expects a variable reference, so we are creating a variable named
// x on the fly.
// src.: https://stackoverflow.com/a/13105389/3514658
$username = "wget";
$email = "wget+devtestemail@wget.be";
// Salt must be at least 22, otherwith password_hash complains
$salt = "I love GNU/Linux especially Arch Linux";
$password_plaintext = "12345";
$password_hashed = password_hash($password_plaintext, PASSWORD_DEFAULT, array("salt" => $salt));
$stmt->bindParam(':username', $username);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':salt', $salt);
$stmt->bindParam(':password', $password_hashed);
$stmt->execute();


// To reset increment values in the database
// SQL: ALTER TABLE hepl_2_webdev_lab_2.user AUTO_INCREMENT = 1;
