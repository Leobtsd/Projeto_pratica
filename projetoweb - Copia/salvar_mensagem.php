<?php
session_start();
include_once 'connection.php';
include_once('validateToken.php');

if (!validationToken()) {
    $_SESSION['msg_validation'] = "Error autenticação, por favor faça o login novamente";
    header('Location: index.php');
    exit();
} 




// Obtenha o token do cookie
$token = $_COOKIE['token']; 

// Divida o token nas partes
list(, $payload,) = explode('.', $token);

// Decodifique o payload do formato Base64
$payload = json_decode(base64_decode($payload), true);

// Acesse o ID do usuário
$userId = $payload['id'];


// Obter dados do formulário
$mensagem = filter_input_array(INPUT_POST, FILTER_DEFAULT);

date_default_timezone_set('America/Sao_Paulo'); 
$dataAtual = date("Y-m-d H:i:s");

try {
    // Inserir mensagem no banco de dados
    $stmt = $connection->prepare("INSERT INTO mensagens (mensagem, user_id, data_publicacao) VALUES (:mensagem, :user_id, :dataAtual)");
    $stmt->bindParam(':mensagem', $mensagem['mensagem'], PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':dataAtual', $dataAtual, PDO::PARAM_STR);
    $stmt->execute();

    // Redirecionar para home.php
    header("Location: home.php");
    exit(); // Certifique-se de sair após o redirecionamento

} catch (PDOException $e) {
    echo "Erro ao enviar a mensagem: " . $e->getMessage();
}

// Fechar a conexão
$connection = null;
?>

