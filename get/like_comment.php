<?php ob_start(); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/initialized_utils.php"); ?>
<?php
$name = $_GET['id'];

if(!isset($_SESSION['siteusername']) || !isset($_GET['id'])) {
    die("You are not logged in or you did not put in an argument");
}

$stmt = $conn->prepare("SELECT * FROM comment_likes WHERE sender = ? AND reciever = ? AND type = 'l'");
$stmt->bind_param("ss", $_SESSION['siteusername'], $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 1) {
        $_user_delete_utils->remove_comment_like($_SESSION['siteusername'], $name);
        goto skip;
    }

$stmt = $conn->prepare("SELECT * FROM comment_likes WHERE sender = ? AND reciever = ? AND type = 'd'");
$stmt->bind_param("ss", $_SESSION['siteusername'], $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 1) {
        $_user_delete_utils->remove_comment_like($_SESSION['siteusername'], $name);
        goto skip;
    }
$stmt->close();

$stmt = $conn->prepare("INSERT INTO comment_likes (sender, reciever, type) VALUES (?, ?, 'l')");
$stmt->bind_param("ss", $_SESSION['siteusername'], $name);

$stmt->execute();
$stmt->close();

skip:
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>