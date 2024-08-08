<?php
require_once "../lib/query.php";
require_once "../../config.php";
session_start();

$parsed_url = parse_url($_SERVER["HTTP_REFERER"]);
$clean_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . $parsed_url['path'];

$oldEmail = $_SESSION['user']['email'];

$username = $_POST['username'];
$email = $_POST['email'];

if (!isset($username) || !isset($email)) {
    header("Location: " . $clean_url . "?error=กรุณากรอกข้อมูลให้ครบถ้วน");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: " . $clean_url . "?error=ที่อยู่อีเมลไม่ถูกต้อง");
    exit();
}

$stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE email = ?");
$stmt->execute([$username, $email, $oldEmail]);
$_SESSION['user'] = getUserByEmail($email)->fetch(PDO::FETCH_ASSOC);
header("Location: " . $clean_url);