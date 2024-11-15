<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$host = 'localhost';
$dbname = 'feuerwehr_mittelberg';
$username = 'root';
$password = '';

$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && isset($_POST['content'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO forum_posts (title, content) VALUES (:title, :content)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->execute();
}

$stmt = $conn->prepare("SELECT * FROM forum_posts ORDER BY created_at DESC");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feuerwehr Mittelberg - Forum</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Forum - Feuerwehr Mittelberg</h1>

        <form action="forum.php" method="POST">
            <label for="title">Titel des Beitrags:</label>
            <input type="text" id="title" name="title" required>

            <label for="content">Inhalt:</label>
            <textarea id="content" name="content" required></textarea>

            <button type="submit">Beitrag erstellen</button>
        </form>

        <h2>Beiträge</h2>
        <div class="posts">
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    <p><small>Erstellt am: <?php echo $post['created_at']; ?></small></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
