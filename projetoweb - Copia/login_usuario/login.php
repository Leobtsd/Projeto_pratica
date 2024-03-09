<?php

session_start();

// Função para exibir mensagens e limpar a variável de sessão
function displayAndClearMessage($messageKey) {
    if (isset($_SESSION[$messageKey])) {
        echo "<p>{$_SESSION[$messageKey]}</p>";
        unset($_SESSION[$messageKey]);
    }
}

// Exibir mensagens de erro, se houver
displayAndClearMessage('msg_form');
displayAndClearMessage('msg_password_incorrect');
displayAndClearMessage('msg_user');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="login.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <header>
        <h1>Login</h1>
    </header>
    <main>
        <form action="login_process" method="POST">
            <label for="use_email">Email:</label>
            <input type="email" name="use_email" id="use_email" placeholder="Seu Email" required><br>

            <label for="use_password">Password:</label>
            <input type="password" name="use_password" id="use_password" placeholder="Sua Senha" required><br>

            <input type="submit" name="SendLogin" value="Enter">
        </form>
    </main>
</body>
</html>


