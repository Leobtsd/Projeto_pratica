<?php 
$host = "localhost";
$port = 3306;
$username = "root";
$password = "hhhhh5";
$database = "projeto_web";

try {
    $connection = new PDO("mysql:host=$host;port=$port;", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $createDatabase = "CREATE DATABASE IF NOT EXISTS $database";
    $connection->exec($createDatabase);

    // Conectar ao banco de dados web_project
    $connection = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $createTableUser = "CREATE TABLE IF NOT EXISTS user (
        use_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        use_name VARCHAR(45) NOT NULL,
        use_email VARCHAR(255) NOT NULL UNIQUE,
        use_password VARCHAR(80) NOT NULL, 
        use_periodo VARCHAR(15) NOT NULL,
        profile_image_path VARCHAR(255) DEFAULT NULL
    )";

    $createTableMensagens = "CREATE TABLE IF NOT EXISTS mensagens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        mensagem TEXT,
        data_publicacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        user_id INT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES user(use_id)
    )";

    $createTableComentarios = "CREATE TABLE IF NOT EXISTS comentarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        comentario TEXT,
        data_publicacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        mensagem_id INT NOT NULL,
        user_id INT NOT NULL,
        FOREIGN KEY (mensagem_id) REFERENCES mensagens(id),
        FOREIGN KEY (user_id) REFERENCES user(use_id)
    )";

    $connection->exec($createTableUser);
    $connection->exec($createTableMensagens);
    $connection->exec($createTableComentarios);

    // echo "Esquema criado com sucesso";

} catch (PDOException $error) {
    die("Connection Failed: " . $error->getMessage());
}
?>

