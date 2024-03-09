<?php 
session_start();

// cadastro efetuado
if(isset($_SESSION['msg_ok'])) {

    echo "<p>" . $_SESSION['msg_ok'] . "</p>";
    unset($_SESSION['msg_ok']);

} 
if(isset($_SESSION['msg_validation'])) {

    echo "<p>" . $_SESSION['msg_validation'] . "</p>";
    unset($_SESSION['msg_validation']);

} 

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<link rel="stylesheet" href="indexx.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyConect</title>
</head>
<body>
    <header>
        <h1>StudyConect</h1>
    </header>
    <main>
        <section class="NavBar">
            <a href="#">Blog</a>
            <a href="#">Sobre</a>
            <a href="#">Linguagens</a>
            <a href="login_usuario/login.php">Login</a>
            <a href="registro/register.php">Register</a>
        </section>    
    </main>
    <footer>
    &copy; 2023 StudyConect
    </footer>
</body>
</html>