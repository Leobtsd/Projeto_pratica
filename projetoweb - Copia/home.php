<?php 

include_once('connection.php');
include_once('model_user.php');
include_once('validateToken.php');

if (!validationToken()) {
    $_SESSION['msg_validation'] = "Error autenticação, por favor faça o login novamente";
    header('Location: index.php');
    exit();
} 


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    charset
    <title>studyConnect</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <a href="logout.php">Exit</a>
    <a href="#">Blog</a>
    <a href="#">Sobre</a>
    <a href="#">Linguagens</a>
    <a href="perfil.php">Perfil</a>
    <h1>StudyConnect</h1>

    <form method="POST" action="salvar_mensagem.php">
        <label for="mensagem">Escreva seu texto:</label>
        <textarea name="mensagem" id="mensagem" required></textarea><br>
        <input type="submit" value="Enviar">
    </form>

    <?php
    // Consulta SQL para obter posts e comentários associados
    $sql = "SELECT mensagens.id,mensagens.user_id, mensagens.mensagem, mensagens.data_publicacao,user.use_periodo, user.use_name,
            COUNT(comentarios.id) AS total_comentarios
            FROM mensagens
            JOIN user ON mensagens.user_id = user.use_id 
            LEFT JOIN comentarios ON mensagens.id = comentarios.mensagem_id
            GROUP BY mensagens.id
            ORDER BY mensagens.data_publicacao DESC";

    try {
        // Preparar e executar a consulta
        $stmt = $connection->prepare($sql);
        $stmt->execute();

        // Exibir as mensagens e comentários
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Adicionar um link para o perfil do usuário apenas ao redor do nome
                echo "<p><a href='perfil.php'><strong>" . $row['use_name'] . "</strong></a> - " . $row['use_periodo'] . "</p>";

                echo "<p>" . $row['mensagem'] . "</p>";
                echo "<p>Data de Publicação: " . $row['data_publicacao'] . "</p>";
                echo "<p>Total de Comentários: " . $row['total_comentarios'] . "</p>";

                // Adicionar um link para resposta.php com um parâmetro, como o ID da mensagem
                echo "<p><a href='resposta.php?id=" . $row['id'] . "'>Responder</a></p>";

                echo "<hr>";
            }
        } else {
            echo "Nenhuma mensagem encontrada.";
        }
    } catch (PDOException $e) {
        die("Erro na execução da consulta: " . $e->getMessage());
    }

    // Fechar a conexão
    $pdo = null;
    ?>
</body>
</html>
