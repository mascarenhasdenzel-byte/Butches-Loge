<?php
$filename = 'posts.txt';
$posts = file_exists($filename) ? file($filename, FILE_IGNORE_NEW_LINES) : [];
$loggedIn = false;
$password = 'HR-Leitung'; // <-- Passwort hier ändern

// Login prüfen
if(isset($_POST['login'])) {
    if($_POST['password'] === $password) {
        $loggedIn = true;
    } else {
        $error = "Falsches Passwort!";
    }
}

// Neuen Beitrag hinzufügen
if(isset($_POST['title'], $_POST['newPost'], $_POST['loginSession']) 
   && $_POST['newPost'] !== '' && $_POST['title'] !== '') {
    $date = date("d.m.Y H:i"); // schöner Zeitstempel
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['newPost']);
    $entry = "<h2>$title</h2><p>$content</p><small>$date</small>\n<hr>\n";
    file_put_contents($filename, $entry, FILE_APPEND);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>BUCHES Portal</title>
<style>
body { font-family: Arial; background:#f4f4f9; color:#333; margin:0; padding:0;}
header { background:#4B0082; color:white; text-align:center; padding:20px;}
.logo { font-size:80px; color:#800000; font-weight:bold;}
main { max-width:800px; margin:20px auto; padding:0 20px;}
article { background:white; padding:15px; margin-bottom:20px; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
textarea, input[type="text"] { width:100%; padding:10px; margin-bottom:10px; border-radius:5px; border:1px solid #ccc;}
button { background:#4B0082; color:white; border:none; padding:10px 20px; border-radius:5px; cursor:pointer;}
button:hover { background:#6A0DAD;}
form { margin-bottom:20px;}
#error { color:red; }
</style>
</head>
<body>
<header>
<div class="logo">B</div>
<h1>BUCHES Portal</h1>
<p>Neuigkeiten aus der BUCHES-Loge</p>
</header>

<main>
<?php if(!$loggedIn): ?>
<form method="post">
<?php if(isset($error)) echo "<div id='error'>$error</div>"; ?>
<input type="password" name="password" placeholder="Passwort eingeben" required>
<button type="submit" name="login">Einloggen</button>
</form>
<?php else: ?>
<form method="post">
<input type="text" name="title" placeholder="Titel des Beitrags" required>
<textarea name="newPost" placeholder="Neuen Beitrag schreiben..." required></textarea>
<input type="hidden" name="loginSession" value="1">
<button type="submit">Beitrag hinzufügen</button>
</form>
<?php endif; ?>

<div id="postsContainer">
<?php
if(!empty($posts)) {
    // Neueste Beiträge oben
    $postsReversed = array_reverse($posts);
    foreach($postsReversed as $p) echo "<article>$p</article>";
}
?>
</div>
</main>
</body>
</html>
