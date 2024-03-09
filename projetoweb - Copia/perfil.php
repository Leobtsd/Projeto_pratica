<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
</head>
<body>

<?php 
session_start();
include_once 'validateToken.php'; 
include_once 'connection.php';

if (!validationToken()) {
    $_SESSION['msg_validation'] = "Error autenticação, por favor faça o login novamente";
    header('Location: index.php');
    exit();
} 

if(isset($_SESSION['msg_update_password'])) {
    echo "<p>" . $_SESSION['msg_update_password'] . "</p>";
    unset($_SESSION['msg_update_password']);
}
if(isset($_SESSION['msg_update'])) {
    echo "<p>" . $_SESSION['msg_update'] . "</p>";
    unset($_SESSION['msg_update']);
}

$token = $_COOKIE['token']; 
$usePeriodo = $_SESSION['user_periodo'];

// Divida o token nas partes
list(, $payload,) = explode('.', $token);

// Decodifique o payload do formato Base64
$payload = json_decode(base64_decode($payload), true);

// Acesse o ID do usuário
$userId = $payload['id'];

// Consulta SQL para obter informações do usuário
$sql = "SELECT use_name, profile_image_path, use_periodo FROM user WHERE use_id = :user_id";

try {
    // Preparar e executar a consulta
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    // Exibir informações do perfil do usuário
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $userName = $row['use_name'];
        $profileImagePath = $row['profile_image_path'];
        $use_periodo = $row['use_periodo'];

        echo "<h1>Perfil de $userName</h1>";

        echo "<a href='home.php'>Home</a>";

        // Exibir a imagem de perfil atual ou a mensagem para adicionar uma imagem
        if (!empty($profileImagePath)) {
            echo "<img src='$profileImagePath' alt='Imagem de Perfil'>";
        } else {
            echo "<p>Adicione uma imagem de perfil.</p>";
        }

        // Exibir o período do usuário
        echo "<p>Período: $use_periodo</p>";

        // Formulário para enviar uma nova imagem
        echo '<form action="upload_profile_image.php" method="post" enctype="multipart/form-data">
                <label for="imagem">Escolha uma imagem de perfil:</label>
                <input type="file" name="imagem" id="imagem" accept="image/*">
                <br>
                <input type="submit" value="Enviar Imagem">
            </form> 
            <br> <br> ';

        // Botão para excluir o perfil
        echo '<form action="excluir_perfil.php" method="post">
                <input type="hidden" name="user_id" value="' . $userId . '">
                <input type="submit" value="Excluir Perfil" onclick="return confirm(\'Tem certeza que deseja excluir seu perfil?\')">
            </form>';

        echo "<h1>Editar Perfil de $userName</h1>";

        echo '<form action="processar_edicao.php" method="post">
        <label for="nome">Novo Nome:</label>
        <input type="text" name="novo_nome" value="' . $userName . '" required>
        <br>
        <label for="senha"> Senha atual:</label>
        <input type="password" name="senha" id="nova_senha" required>
        <br>
        <label for="confirmar_senha"> Nova Senha:</label>
        <input type="password" name="nova_senha" required>
        <br>
        <input type="hidden" name="id" value="' . $userId . '">
        <label for="periodo">Novo Período:</label>
        <select name="novo_periodo" required>';

        // Loop para criar as opções de 1 a 10
        for ($i = 1; $i <= 10; $i++) {
            // Verifica se a opção atual é igual ao período atual do usuário
            $selected = ($i == $use_periodo) ? 'selected' : '';

            echo '<option value="' . $i . '" ' . $selected . '>' . $i . ' Periodo</option>';
        }

        echo '</select>
                <br>
                <input type="submit" name="update_user" value="Salvar Alterações">
            </form>';


    } 
} catch (PDOException $e) {
    die("Erro na execução da consulta: " . $e->getMessage());
}

// Fechar a conexão
$connection = null;
?>
</body>
</html>

