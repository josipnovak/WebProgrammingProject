<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        include 'auth/register.php';
        if (isset($error)) {
            echo "<p style='color:red;'>$error</p>";
        } elseif (isset($success)) {
            echo "<p style='color:green;'>$success</p>";
        }
    } elseif (isset($_POST['login'])) {
        include 'auth/login.php';
        if (isset($error)) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}

if (isset($_SESSION['username'])) {
    echo "<h1>Dobrodošao, " . htmlspecialchars($_SESSION['username']) . "!</h1>";
    include 'includes/nav.php';
    echo "<p>Uloga: " . htmlspecialchars($_SESSION['role']) . "</p>";
    echo '<form method="post" action="auth/logout.php"><button name="logout" type="submit">Odjavi se</button></form>';
    
} else {
    include 'includes/nav.php';
    ?>
    <h2>Prijava</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Korisničko ime" required><br><br>
        <input type="password" name="password" placeholder="Lozinka" required><br><br>
        <button type="submit" name="login">Prijavi se</button>
    </form>
    
    <hr>
    
    <h2>Registracija</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Korisničko ime" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Lozinka" required><br><br>
        <input type="password" name="confirm_password" placeholder="Potvrdi lozinku" required><br><br>
        <button type="submit" name="register">Registriraj se</button>
    </form>

    <?php
}
?>

</body>
</html>