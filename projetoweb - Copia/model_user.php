<?php 

include_once("connection.php");
session_start();


class User {


     private $connection;


     public function __construct(PDO $connection) { // conexão banco
        if ($connection === null) {
            throw new InvalidArgumentException("Conexão inválida");
            // Se $conection for nulo, a instrução throw gera uma exceção. Neste caso, está sendo lançada uma exceção do tipo InvalidArgumentException com a mensagem "Conexão inválida"
        }
        $this->connection = $connection;
    }
    

    public function registerUser($name, $email, $password, $periodo){ // função que registra um novo usuário no banco

        if(isset($name) && isset($email) && isset($password) && isset($periodo)) {

                // Verifique se o e-mail já existe na tabela
                $checkEmail = "SELECT COUNT(*) FROM user WHERE use_email = :email";
                $stmtCheck = $this->connection->prepare($checkEmail);
                $stmtCheck->bindParam(':email', $email, PDO::PARAM_STR);
               
               try {   

                $stmtCheck->execute();

               } catch (PDOException $e) {

                echo "Error" . $e->getMessage();
            }

                $emailExists = $stmtCheck->fetchColumn();

        if ($emailExists) {

            // E-mail já existe, envie uma mensagem de erro
            $_SESSION['msg_email'] = "E-mail já cadastrado. Tente outro e-mail";
            
            header("Location: register.php");
            exit(); // encerrar o script
        }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // criptografar senha


            $insert = "INSERT INTO user (use_name, use_email, use_password, use_periodo) VALUES (:name, :email, :password, :periodo)";
            $stmt = $this->connection->prepare($insert);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->bindParam(':periodo', $periodo, PDO::PARAM_STR);

            try {

                $stmt->execute();

                $_SESSION['msg_ok'] = "Cadastro realizado com sucesso! Já pode efetuar o login";

                header("Location: ../index.php");
                exit();
                
            } catch (PDOException $e) {

                echo "Error" . $e->getMessage();
            }


           

        } else {
            $_SESSION['msg_msg'] = "Preencha todos os campos";

            header("Location: register.php");
            exit();
        }
    }

    public function LoginUser($email, $password) { // login

        //recupera os dados do usuário no banco através do email
        $query = "SELECT * FROM user WHERE use_email = :email LIMIT 1";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException $e) {

            echo "Error" . $e->getMessage();
        }
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($user) { // se tiver dados referente ao usuário..

            if(password_verify($password, $user['use_password'])) { // verifica se a senha digitada coincide com a do banco
                $_SESSION['user_name'] = $user['use_name'];
                $_SESSION['user_periodo'] = $user['use_periodo'];
                //$_SESSION['user_id'] = $user['use_id'];
               // $_SESSION['user_email'] = $user['use_email']; 
                 
                //////////////////////////////////
                $header = [
                    'alg' => 'HS256',
                    'typ' => 'JWT'
                ]; 
                
                $header = json_encode($header);
                
                $header = base64_encode($header);
                
                $timerexpiration = time() + (3600);
                
                $payload = [
                    
                    'exp' => $timerexpiration,
                    'id' => $user['use_id'],
                    'name' => $user['use_name'],
                    'email' => $user['use_email']
                ];
                
                $payload = json_encode($payload);
                
                $payload = base64_encode($payload);
                
                $key = "HDHFDKFHDKFJOO56KC";
                
                $signature = hash_hmac('sha256', "$header.$payload", $key, true);
                
                $signature = base64_encode($signature);
                
                //var_dump($signature);
                
               // echo "<br> $signature.$payload";

            
                /*  Defina o cookie com o parâmetro 'httponly' como true
                    true: Define o cookie apenas para ser transmitido por HTTPS se estiver configurado.
                    true: Define o parâmetro 'httponly' para true.
                    Dessa forma, o cookie será marcado como httponly, o que significa que ele só será acessível através do lado do servidor e não poderá ser manipulado por scripts do lado do cliente, tornando-o mais seguro contra ataques de script entre sites (XSS). */
                setcookie('token', "$header.$payload.$signature", (time() + (3600)), '/', '', true, true);

               // session_regenerate_id();
                
                /////////////////////////////////////////////////////////////////
                header("location: ../home.php");
                exit();
            } else {
                $_SESSION['msg_password_incorrect'] = "Senha incorreta";
                header("Location: login.php");
                exit();
            }

        } else {
            $_SESSION['msg_user'] = "Usuário não encontrado, por favor verifique o email inserido ou crie uma conta";
            header("Location: login.php");
            exit();
        }

    }

    public function UpdateUser($name, $password, $newpassword, $periodo, $id) {

        if (isset($name) && isset($periodo) && isset($password) && $newpassword) {

            $query = "SELECT * FROM user WHERE use_id = :id LIMIT 1";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    
            try {
                $stmt->execute();
            } catch (PDOException $e) {
    
                echo "Error" . $e->getMessage();
            }
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if(password_verify($password, $user['use_password'])) {

                $hashPassword = password_hash($newpassword, PASSWORD_DEFAULT); // criptografar senha

                $stmt = $this->connection->prepare('UPDATE user SET use_name = :name, use_periodo = :periodo, use_password = :newpassword WHERE use_id = :id');
                $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                $stmt->bindParam(":periodo", $periodo, PDO::PARAM_STR);
                $stmt->bindParam(":newpassword", $hashPassword, PDO::PARAM_STR);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                
                
                try {

                $stmt->execute();

                $_SESSION['msg_update'] = "Cadastro atualizado com sucesso!";
                $_SESSION['user_name'] = $name;
                //$_SESSION['email_user'] = $email;

                header("Location: perfil.php");
                exit();
                
                } catch (PDOException $e) {

                echo "Error" . $e->getMessage();
                }

            } else {

                $_SESSION['msg_update_password'] = "Senha atual incorreta";
                header("Location: perfil.php");
                exit();
            }

        } else {

            $_SESSION['msg_update_falid'] = "Erro no envio dos dados, por favor preencha novamente";
                header("Location: perfil.php");
                exit();
        }
    }

    public function DeleteUser($userId) {
        if($userId) {
            try {
                // Consulta SQL para excluir as mensagens associadas ao usuário
                $deleteMessagesSql = "DELETE FROM mensagens WHERE user_id = :user_id";
                $deleteMessagesStmt = $this->connection->prepare($deleteMessagesSql);
                $deleteMessagesStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $deleteMessagesStmt->execute();
    
                // Consulta SQL para excluir os comentários associados ao usuário
                $deleteCommentsSql = "DELETE FROM comentarios WHERE user_id = :user_id";
                $deleteCommentsStmt = $this->connection->prepare($deleteCommentsSql);
                $deleteCommentsStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $deleteCommentsStmt->execute();
    
                // Consulta SQL para excluir o usuário
                $deleteUserSql = "DELETE FROM user WHERE use_id = :user_id";
                $deleteUserStmt = $this->connection->prepare($deleteUserSql);
                $deleteUserStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $deleteUserStmt->execute();
    
                // Limpar o cookie de token
                setcookie('token', '', time() - 3600, '/', '', true, true);
    
                // Destruir a sessão
                session_destroy();
    
                $_SESSION['user_delete_ok'] = "Usuário excluído com sucesso";
    
                // Redirecionar para a página de login ou outra página após o logout
                header("Location: index.php");
                exit();
            } catch (PDOException $e) {
                $_SESSION['user_delete_error'] = "Erro ao excluir usuário: " . $e->getMessage();
                header("Location: perfil.php");
                exit();
            }
        } else {
            $_SESSION['user_delete_error'] = "Erro, por favor, tente novamente";
            header("Location: profile.php");
            exit();
        }
    }
    

    

    public function UpdateImage($caminho, $userId) {

        $update_profile_image = "UPDATE user SET profile_image_path = :profile_image_path WHERE use_id = :userId";
        $stmt = $this->connection->prepare($update_profile_image);
        $stmt->bindParam(':profile_image_path', $caminho, PDO::PARAM_STR);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

        try {
            $stmt->execute();

                $_SESSION['image_ok'] = "Imagem adicionada com sucesso!";

                header("Location: perfil.php"); // Redireciona de volta para o perfil após o upload
                
            } catch (PDOException $e) {
                echo "Erro ao atualizar o caminho da imagem de perfil: " . $e->getMessage();
            }
        }
}



?>