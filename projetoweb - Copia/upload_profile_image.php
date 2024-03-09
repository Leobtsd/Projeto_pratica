<?php
session_start();
include_once 'validateToken.php';
include_once 'connection.php';
include_once 'model_user.php';

if (!validationToken()) {
    $_SESSION['msg_validation'] = "Error autenticação, por favor faça o login novamente";
    header('Location: index.php');
    exit();
} 

$token = $_COOKIE['token']; 

// Divida o token nas partes
list(, $payload,) = explode('.', $token);

// Decodifique o payload do formato Base64
$payload = json_decode(base64_decode($payload), true);

// Acesse o ID do usuário
$userId = $payload['id'];

    

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Diretório onde as imagens serão armazenadas
    $diretorioDestino = "imagens/";

    // Caminho completo da imagem (diretório + nome do arquivo)
    $caminhoImagem = $diretorioDestino . basename($_FILES["imagem"]["name"]);

    // Move o arquivo para o diretório de destino
    if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminhoImagem)) {
        // O upload foi bem-sucedido, agora você pode armazenar $caminhoImagem no banco de dados
        $caminhoNoBancoDeDados = $caminhoImagem;

        
        $User = new User($connection);

        $User->UpdateImage($caminhoNoBancoDeDados, $userId);
        
    } else {
        echo "Erro ao fazer o upload da imagem.";
    }
}
?>



