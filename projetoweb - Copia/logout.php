<?php 

session_start();

 // Define o cookie com uma data de expiração no passado (para removê-lo)
 setcookie('token', '', time() - 3600, '/', '', true, true);

 // Destroi a sessão
 session_destroy();

 // Redireciona para a página de login (ou qualquer outra página após o logout)
 header("Location: index.php");
 exit();