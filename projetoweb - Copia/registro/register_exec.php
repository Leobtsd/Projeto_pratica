<?php 

include_once("../model_user.php");
include_once("../connection.php");




if (!empty($_POST['sendRegisterUser'])) { // verifica se o formulário não está vazio

    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT); // filtra os dados e transforma o formulário recebido em um array assossiativo 

    if ($dados['use_password'] !== $dados['confirm_password']) {
        
        $_SESSION['msg_password'] = "As senhas não coincidem";

        header("Location: register.php");
        exit();

        } else {


            $user = new User($connection);

            $user->registerUser($dados['use_name'], $dados['use_email'], $dados['use_password'], $dados['use_periodo']); // cadastrar dados no banco


            header("Location: index.php");
            exit();
        }

} else {

    $_SESSION['msg_form'] = "Erro no envio do formuário, por favor preencha novamente";

    header("Location: register.php");
    exit();
}




?>