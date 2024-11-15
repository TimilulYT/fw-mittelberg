<?php
session_start();
$host = 'localhost';
$dbname = 'feuerwehr_mittelberg';
$username = 'root';
$password = '';  // Ändere es zu deinem MySQL Passwort

$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $user);
    $stmt->execute();
    
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($userData && password_verify($pass, $userData['password'])) {
        $_SESSION['user_id'] = $userData['id'];
        header('Location: forum.php');
    } else {
        echo "Falscher Benutzername oder Passwort!";
    }
}
?>
