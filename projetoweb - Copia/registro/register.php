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
displayAndClearMessage('msg_password');
displayAndClearMessage('msg_form');
displayAndClearMessage('msg_email');
displayAndClearMessage('msg_msg');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="register.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <header>
        <h1>Register User</h1>
    </header>

    <section class="NavBar">
        <a href="#">Blog</a>
        <a href="#">Sobre</a>
        <a href="#">Linguagens</a>
        <a href="../login_usuario/login.php">Login</a>
        <a href="../index.php">Início</a>
    </section>

    <form action="register_exec.php" method="POST">
        <label>Nome:</label>
        <input type="text" minlength="3" maxlength="150" required name="use_name" placeholder="Seu nome">
        
        <label>Email:</label>
        <input type="email" required maxlength="150" name="use_email" placeholder="Seu e-mail">
        
        <label>Senha:</label>
        <input type="password" minlength="8" maxlength="20" required name="use_password" placeholder="Mínimo 8 caracteres">
        
        <label>Confirmar senha:</label>
        <input type="password" minlength="8" maxlength="20" required name="confirm_password" placeholder="Repita a senha">
        
        <label for="lista-itens">Qual o seu período?</label>
        <select id="lista-itens" name="use_periodo" placeholder="Periodo">
            <?php
            for ($i = 1; $i <= 10; $i++) {
                echo "<option value='{$i} Periodo'>{$i} Periodo</option>";
            }
            ?>
        </select>
        <br>
        <br>
        <input type="submit" name="sendRegisterUser" value="Registrar">
    </form>

    <a href="index.php">Index</a>

    <footer>
       &copy; <?php echo date("Y"); ?> StudyConnect
    </footer>
</body>
</html>


